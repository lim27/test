const btn_load = document.getElementById("loadEmployees");

btn_load.addEventListener("click", function () {
  let loadData = new XMLHttpRequest();
  loadData.open("POST", "/content/load_employees.php", true);
  loadData.setRequestHeader("Content-Type", "application/json");
  loadData.onreadystatechange = function () {
    if (loadData.readyState === 4 && loadData.status === 200) {
      alert("Данные успешно загружены");
    } else {
    }
  };
  loadData.send();
});

const btn_getData = document.getElementById("getEmployees");

btn_getData.addEventListener("click", function () {
    let element = document.getElementById("infoEmployees");

    if (element.style.display === 'none') {
        element.style.display = 'block';
      } else {
        element.style.display = 'none';
      }
});
