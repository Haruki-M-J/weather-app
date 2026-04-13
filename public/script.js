const apiKey = "09e12204cbfc5f595ef342075eaee897";

async function save() {
  const city = document.getElementById("city").value;
  const memo = document.getElementById("memo").value;

  if (!city) {
    alert("都市名を入力してください");
    return;
  }

  // 天気取得
  const res = await fetch(
    `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`,
  );
  const data = await res.json();
  console.log(data);

  if (!data.weather) {
    alert("都市が見つかりません");
    return;
  }

  const weather = data.weather[0].description;

  // DB保存
  await fetch("/api/save.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ city, weather, memo }),
  });

  load();
}

async function load() {
  const res = await fetch("/api/get.php");
  const data = await res.json();

  const result = document.getElementById("result");
  result.innerHTML = "";

  data.forEach((item) => {
    const div = document.createElement("div");
    div.className = "card";
    div.innerHTML = `
            <p>${item.city} / ${item.weather}</p>
            <p>${item.memo}</p>
            <button onclick="deleteData(${item.id})">削除</button>
        `;
    result.appendChild(div);
  });
}

async function deleteData(id) {
  await fetch("/api/delete.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id }),
  });

  load();
}

window.onload = load;
