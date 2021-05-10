function getHistory() {
  window.location = "order_history.php";
}

function setAddress() {
  window.location = "set_default_address.php";
}

function setPayment() {
  window.location = "set_default_payment.php";
}

function notLoginIn() {
  alert("Uh-oh, you are not logined in");
  window.location = "../front_page/front_page.php";
}