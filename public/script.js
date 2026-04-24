/**
 * 保存処理
 */
window.save = async function () {
    const cityInput = document.getElementById("city");
    const memoInput = document.getElementById("memo");
    const city = cityInput.value.trim();
    const memo = memoInput.value.trim();

    if (!city) {
        alert("都市名を入力してください");
        return;
    }

    try {
        // 天気取得
        const weatherRes = await fetch(`/api/weather.php?city=${encodeURIComponent(city)}`);
        const weatherData = await weatherRes.json();

        if (!weatherRes.ok) {
            alert(weatherData.message || "天気情報を取得できませんでした");
            return;
        }

        const weather = weatherData.weather;

        // DB保存
        const saveRes = await fetch("/api/save.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ city, weather, memo })
        });

        const saveData = await saveRes.json();

        if (!saveRes.ok) {
            alert(saveData.message || "保存に失敗しました");
            return;
        }

        cityInput.value = "";
        memoInput.value = "";
        load();

    } catch (err) {
        console.error(err);
        alert("通信エラーが発生しました");
    }
};


/**
 * 一覧取得
 */
async function load() {
    try {
        const res = await fetch("/api/get.php");
        const data = await res.json();

        if (!res.ok || !Array.isArray(data)) {
            throw new Error(data.message || "Failed to load");
        }

        const result = document.getElementById("result");
        result.innerHTML = "";

        if (data.length === 0) {
            result.textContent = "保存されたデータはまだありません。";
            return;
        }

        data.forEach(item => {
            const div = document.createElement("div");
            div.className = "card";

            const p1 = document.createElement("p");
            p1.textContent = `${item.city} / ${item.weather}`;

            const p2 = document.createElement("p");
            p2.textContent = item.memo;

            const btn = document.createElement("button");
            btn.textContent = "削除";
            btn.onclick = () => deleteData(item.id);

            div.appendChild(p1);
            div.appendChild(p2);
            div.appendChild(btn);

            result.appendChild(div);
        });

    } catch (err) {
        console.error(err);
        alert("データ取得に失敗しました");
    }
}


/**
 * 削除処理
 */
async function deleteData(id) {
    try {
        const res = await fetch("/api/delete.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        });

        const data = await res.json();

        if (!res.ok) {
            alert(data.message || "削除に失敗しました");
            return;
        }

        load();

    } catch (err) {
        console.error(err);
        alert("通信エラー");
    }
}


/**
 * 初期読み込み
 */
window.onload = load;
