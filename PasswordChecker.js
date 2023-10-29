const SignUpPasswordStatus = document.getElementById("PasswordStatus");
const WeakPassword = document.getElementById("PasswordWeak");
const AveragePassword = document.getElementById("PasswordAverage");
const StrongPassword = document.getElementById("PasswordStrong");
const Match = document.getElementById("Match");
const DontMatch = document.getElementById("DontMatch");
const WeakMeter = document.getElementById("Weak");
const AverageMeter = document.getElementById("Average");
const StrongMeter = document.getElementById("Strong");
var SignUpPassword = document.getElementById("Password");
var SignUpConfirmPassword = document.getElementById("ConfirmPassword");
var Password = document.getElementById("Password");
var Toggle = document.getElementById("Toggle");
var ConPassword = document.getElementById("ConfirmPassword");
var Toggle1 = document.getElementById("Toggle1");

function ShowHide(){
  if(Password.type === "password"){
    Password.setAttribute("type", "text");
    Toggle.classList.add("Show");
  } else {
    Password.setAttribute("type", "password");
    Toggle.classList.remove("Show");
  }
}

function ShowHide1(){
  if(ConPassword.type === "password"){
    ConPassword.setAttribute("type", "text");
    Toggle1.classList.add("Show");
  } else {
    ConPassword.setAttribute("type", "password");
    Toggle1.classList.remove("Show");
  }
}

SignUpPassword.addEventListener("keyup", function(){
  if(SignUpPassword.value.length != 0){
    if(SignUpPassword.value.length < 6 || SignUpPassword.value.match(/[a-z]/) | (SignUpPassword.value.match(/[a-z]/) && SignUpPassword.value.match(/[A-Z]/))){
      SignUpPasswordStatus.style.display = "none";
      WeakPassword.style.display = "block";
      WeakMeter.style.background = "#FF4757";
      DontMatch.style.display = "none";
      Match.style.display = "none";
    }
    if((SignUpPassword.value.length >= 6 && SignUpPassword.value.match(/^[a-zA-Z]/g) && SignUpPassword.value.match(/[0-9]/) && !SignUpPassword.value.match(/\W|_/g)) || (SignUpPassword.value.length >=6 && SignUpPassword.value.match(/^[a-zA-Z]/g) && SignUpPassword.value.match(/\W|_/g) && !SignUpPassword.value.match(/[0-9]/))){
      SignUpPasswordStatus.style.display = "none";
      WeakPassword.style.display = "none";
      AveragePassword.style.display = "block";
      StrongPassword.style.display = "none";
      WeakMeter.style.background = "#FF4757";
      AverageMeter.style.background = "orange";
      StrongMeter.style.background = "lightgray";
      DontMatch.style.display = "none";
      Match.style.display = "none";
    } else {
      WeakPassword.style.display = "block";
      AveragePassword.style.display = "none";
      AverageMeter.style.background = "lightgray";
      if(SignUpPassword.value.length >=6 && SignUpPassword.value.match(/^[a-zA-Z]/g) && SignUpPassword.value.match(/[0-9]/) && SignUpPassword.value.match(/\W|_/g)){
          SignUpPasswordStatus.style.display = "none";
          WeakPassword.style.display = "none";
          AveragePassword.style.display = "none";
          StrongPassword.style.display = "block";
          WeakMeter.style.background = "#FF4757";
          AverageMeter.style.background = "orange";
          StrongMeter.style.background = "#23AD5C";
          DontMatch.style.display = "none";
          Match.style.display = "none";
        } else {
          WeakPassword.style.display = "block";
          AveragePassword.style.display = "none";
          StrongPassword.style.display = "none";
          StrongMeter.style.background = "lightgray";
        }
      }
  } else {
    SignUpPasswordStatus.style.display = "block";
    WeakPassword.style.display = "none";
    AveragePassword.style.display = "none";
    StrongPassword.style.display = "none";
    WeakMeter.style.background = "lightgray";
    AverageMeter.style.background = "lightgray";
    StrongMeter.style.background = "lightgray";
    DontMatch.style.display = "none";
    Match.style.display = "none";
  }
})

SignUpConfirmPassword.addEventListener("keyup", function(){
  if(SignUpConfirmPassword.value.length != 0){
    if(SignUpConfirmPassword.value != SignUpPassword.value){
      DontMatch.style.display = "block";
      Match.style.display = "none";
      SignUpPasswordStatus.style.display = "none";
      WeakPassword.style.display = "none";
      AveragePassword.style.display = "none";
      StrongPassword.style.display = "none";
      WeakMeter.style.background = "#FF4757";
      AverageMeter.style.background = "#FF4757";
      StrongMeter.style.background = "#FF4757";
    } else {
      DontMatch.style.display = "none";
      Match.style.display = "block";
      SignUpPasswordStatus.style.display = "none";
      WeakPassword.style.display = "none";
      AveragePassword.style.display = "none";
      StrongPassword.style.display = "none";
      WeakMeter.style.background = "#23AD5C";
      AverageMeter.style.background = "#23AD5C";
      StrongMeter.style.background = "#23AD5C";
    }
  } else {
    DontMatch.style.display = "none";
    Match.style.display = "none";
  } 
})