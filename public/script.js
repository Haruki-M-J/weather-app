const apiKey = "09e12204cbfc5f595ef342075eaee897";

/**
 * 保存処理
 */
window.save = async function () {
    const city = document.getElementById("city").value;
    const memo = document.getElementById("memo").value;

    if (!city) {
        alert("都市名を入力してください");
        return;
    }

    try {
        // 天気取得
        const weatherRes = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`
        );

        const weatherData = await weatherRes.json();

        if (weatherData.cod !== 200) {
            alert("都市が見つかりません");
            return;
        }

        const weather = weatherData.weather[0].description;

        // DB保存
        const saveRes = await fetch("/api/save.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ city, weather, memo })
        });

        const saveText = await saveRes.text();
        console.log("save response:", saveText);

        if (!saveRes.ok) {
            alert("保存に失敗しました");
            return;
        }

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

        const result = document.getElementById("result");
        result.innerHTML = "";

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

        if (!res.ok) {
            alert("削除失敗");
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
