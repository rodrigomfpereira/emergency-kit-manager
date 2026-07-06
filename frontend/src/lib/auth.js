export function isAuth() {
let isAuth = false;
let token = localStorage.getItem("token") ? localStorage.getItem("token") : null ;
if(token != null){
if (token.length >= 35)
isAuth = true;
}
return isAuth;
}