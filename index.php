<?php
include 'sh/sh.php';
if(isset($_POST['submit'])){
  $q = $_POST['search'];
  $q = str_replace(' ','-',$q);
  $html = file_get_html('https://'.$q.'.mp3quack.lol');
  $b .= <<<EOF
  <script>
  window.onload = function(){
  var i,url;
  var arr = document.getElementsByTagName('div');
  var img_url = [];
  for (i=0;i<arr.length;i++){
    if (arr[i].getAttribute('data-image')){
      img_url.push(arr[i].getAttribute('data-image'));      
    }
  }
  var arr2 = document.getElementsByTagName('div');
  var down_url = [];
  for (i=0;i<arr.length;i++){
    if (arr[i].getAttribute('data-media')){
      down_url.push(geturl(arr[i].getAttribute('data-media')));
    }
  }
  img_url.shift();
  sessionStorage.setItem('cover',img_url.join('<!--!>'));
  sessionStorage.setItem('id',down_url.join('<!--!>'));
  var titles = [document.getElementsByTagName('h1')[0].innerText];
  var lk = document.getElementsByTagName('h3');
  for (i=0;i<lk.length;i++){
    titles.push(lk[i].innerHTML);
  }
  sessionStorage.setItem('titles',titles.join('<!--!>'));
  var jk = document.getElementsByClassName('music-info')[0].innerHTML.split('</li>');
  jk.shift();
  var deets = [jk.join('</li>')];
  lk = document.getElementsByClassName('mf-info');
  for (i=0;i<lk.length;i++){
    deets.push(lk[i].innerHTML);
  }
  sessionStorage.setItem('deets',deets.join('<!--!>'));
  window.open('/download','_top');
}
  </script>
EOF;
  foreach($html->find('.leading') as $element)
        $b.=$element;
  foreach($html->find('.results') as $element)
        $b.=$element;
  echo $b;
}
?>
<html>
<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
* {
  box-sizing: border-box;
  outline:none;
}

.leading, .results {
  display:none;
}

body {
  font: 16px Arial;  
  background-image:url('https://i.pinimg.com/originals/f6/29/1b/f6291b3283f38b681b662765ac298c0f.jpg');
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;  
}

.autocomplete ,.a2{
  position: relative;
  display: inline-block;
}

input {
  border: 1px solid transparent;
  background-color: #000;
  padding: 10px;
  font-size: 16px;
  color:#fff!important;
}

input[type=text] {
  background-color: rgba(255,255,255,0.2);
  width: 100%;
  border: 1px solid #d4d4d4;
}

::placeholder{
  color:#fff;
}

button{
    background: transparent;
    color:#fff;
    position: absolute;
    right: 0;
    z-index: 2;
    font-size: 25px;
    color: #ffffff;
    margin: -18px 7px;
    border:none;
}

input[type=text]:focus {
    background-color:#000;
}    

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  top: 100%;
  left: 0;
  right: 0;
  color:#fff;
}

.autocomplete-items div:hover{
    background-color:transparent;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #000; 
  border-bottom: 1px solid #d4d4d4; 
}



</style>
</head>
<body>
<div id="main" style="margin-top:15%;"> 
<div class="background"><span class="bg-image"></span></div>
<center>
<strong><h1 style="color:#fff;font-size:350%!important;cursor:pointer;" onclick="window.open('/','_top')">GETMP3</h1></strong>
<form autocomplete="off" id="form" method="post" action=''>
  <div class="autocomplete" style="width:80%;">
    <input id="search" type="text" name="search" placeholder="Search for a song">
  </div>
  <div class="a2"><button type="submit" id="sub" name="submit"><i class="fa fa-search"></i></button></div>
</form>
</div>
</center>
<script>
sessionStorage.clear();
function autocomplete(inp, arr) {
  var currentFocus;
  if (1) {
      var a, b, i, val = inp.value;
      closeAllLists();
      currentFocus = -1;
      a = document.createElement("DIV");
      a.setAttribute("id", inp.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      inp.parentNode.appendChild(a);      
      for (i = 0; i < arr.length; i++) {
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          b = document.createElement("DIV");
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          b.addEventListener("click", function(e) {
              inp.value = this.getElementsByTagName("input")[0].value;            
              closeAllLists();
              document.getElementById('sub').click();
          });
          a.appendChild(b);
        }
      }
  };
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(inp.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      addActive(x);
      if (e.keyCode == 40) {
        currentFocus++;        
      } else if (e.keyCode == 38) { 
        currentFocus--;
        addActive(x);
      } else if (e.keyCode == 13) {
        e.preventDefault();
        if (currentFocus > -1) {
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    if (!x) return false;
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);    
    x[currentFocus].classList.add("autocomplete-active");
  }    
  function removeActive(x) {
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}
function close() {
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
        x[i].parentNode.removeChild(x[i]);
    }
}
val = document.getElementById('search');
setInterval(() => {
  if (val.value.length == 0){
    close();
  }
}, 5);
val.addEventListener('input',function(){
        if (val.value.length > 2){
            $.ajax({
                url:"https://suggestqueries.google.com/complete/search",
                dataType:"jsonp",
                data:{client:"firefox",ds:"yt",q:val.value},
                success:function(d){
                    autocomplete(val,d[1]);
                }
            });}
});
hex2ascii2 = function(hex) {
  hex = hex.toString();
  var ret = "";
  var i = 0;
  for (; i < hex.length && "00" !== hex.substr(i, 2); i = i + 2) {
    ret = ret + String.fromCharCode(parseInt(hex.substr(i, 2), 16));
  }
return ret;};
geturl = function(u){
  return hex2ascii2(u.substr(10,22));
}
</script>
</body>
</html>