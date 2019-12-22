/**
 * HttpPost - http post request
 * @param string   url  Http request url
 * @param array   arr  Post data array: var data = []; data['id'] = 1; data['cmd'] = 'get-user';
 * @param function cb   Callback function with response parametr
 * @param form obj   form Form object $('#form')[0];
 * 
 * hr.setRequestHeader("Content-Type", "application/json"); 
 * data:
 * var json = {"email": "hey@mail.xx", "password": "101010"}
 * var data = JSON.stringify(json)
 * 
 * hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 * data: 
 * "fname=Henry&lname=Max"
 */
function HttpPost(url, arr, cb, form){
    if (form === undefined) { var data = new FormData(); }else{ var data = new FormData(form); }
    if (arr !== undefined) {
        for (const index in arr) {    
            data.append(index, arr[index]);
        }
    }
    var hr = new XMLHttpRequest();
    hr.onreadystatechange=function(){
        if (hr.readyState==4 && hr.status==200){
            if( typeof cb === 'function' ){ cb(hr.responseText); }
        }
    }
    hr.upload.onprogress = function(e) {
        var done = e.position || e.loaded, total = e.totalSize || e.total;
        console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');
    };
    hr.open("POST",url,true);
    hr.send(data);
}
// HttpPost callback
function cb(res){
    console.log(res);
    var json = JSON.parse(res);
    console.log(json.error + ' ' + json.list);
    // loop
    for (var obj in json.list){     
        console.log(json.list[obj].id + ' ' + json.list[obj].name);
    }
}

/**
 * @param id - input field object
 * <input type="file" name="file" id="image" onchange="FileBase64OrBlob(this,0);" enctype="multipart/form-data">
 * @param: blob - if set to 1 display file blob path
 * @sample
 * // get file from input
 * var inp = $('#image')[0];
 * var img = FileBase64OrBlob(inp,0);
 */
async function FileBase64OrBlob(id, blob = 0) {
	let files = id.files;
	let file = files[0];
	let result = "";
	// Base64
    let result_base64 = await new Promise((resolve) => {
        	let fileReader = new FileReader();
        	fileReader.onload = (e) => resolve(fileReader.result);
        	fileReader.readAsDataURL(file);
	});
	result = result_base64;    
	// Blob
	if(blob != 0){
		// Get blob
	result = window.URL.createObjectURL(file);		
	}
	console.log("Image: " + result);
	return result;
}

function checkDec(el){  
    var ex = /^[0-9]+\.?[0-9]{0,2}$/;
    if(ex.test(el.value)==false){
        el.value = el.value.substring(0,el.value.length - 1);
    }
}

function checkDecLocation(el){
    var ex = /^\-?[0-9]+\.?[0-9]{0,6}$/;
    if(ex.test(el.value)==false){
        el.value = el.value.substring(0,el.value.length - 1);
    }
}

function checkEmail(el){    
    var ex = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/;
    if(ex.test(el.value)==false){
        el.style.color = '#f23';
    }else{
        el.style.color = '#222';
    }
}

function checkDomain(el){   
    var ex = /((http|https?):\/\/)?(www\.)?[a-z0-9\-\.]{2,}\.[a-z]{2,}$/;
    if(ex.test(el.value)==false){
        el.style.color = '#f23';
    }else{
        el.style.color = '#222';
    }
}

function checkMobile(el){   
    var ex = /^\+[0-9]{1,4}( [0-9]{3}){3}$/;
    if(ex.test(el.value)==false){
        el.style.color = '#f23';
    }else{
        el.style.color = '#222';
    }
}

function validateNip(nip) {
    var nipWithoutDashes = nip.replace(/-/g,"");
    var reg = /^[0-9]{10}$/;
    if(reg.test(nipWithoutDashes) == false) {
        return false;
    }else{
        var digits = (""+nipWithoutDashes).split("");
        var checksum = (6*parseInt(digits[0]) + 5*parseInt(digits[1]) + 7*parseInt(digits[2]) + 2*parseInt(digits[3]) + 3*parseInt(digits[4]) + 4*parseInt(digits[5]) + 5*parseInt(digits[6]) + 6*parseInt(digits[7]) + 7*parseInt(digits[8]))%11;         
        return (parseInt(digits[9])==checksum);
    }
}

function validateRegon(regon) {
    var reg = /^[0-9]{9}$/;
    if(!reg.test(regon)){ 
        return false;
    }else{
        var digits = (""+regon).split("");
        var checksum = (8*parseInt(digits[0]) + 9*parseInt(digits[1]) + 2*parseInt(digits[2]) + 3*parseInt(digits[3]) + 4*parseInt(digits[4]) + 5*parseInt(digits[5]) + 6*parseInt(digits[6]) + 7*parseInt(digits[7]))%11;
        if(checksum == 10){
            checksum = 0;
        }
        return (parseInt(digits[8])==checksum);
    }
}

function checkNip(el){
    if(validateNip(el.value)==false){
        el.style.color = '#f23';
    }else{
        el.style.color = '#222';
    }
}

function checkRegon(el){        
    if(validateRegon(el.value)==false){
        el.style.color = '#f23';
    }else{
        el.style.color = '#222';
    }
}

// If errro image show default image 
// <img src="" onerror="imgErrorUser(this);">
function imgErrorUser(img){    
    img.src = '/media/img/user-green.png';
}

// if image not exist
function imageExist(id){
    // console.log(window.location);
    var url = '/media/products/category-'+id+'.jpg';    
    var img = new Image();
    img.src = url;    
    if(img.height > 0){
        return url;
    }else{
        return '/media/img/food.png';
    }   
}

// check url
function urlExists(url){
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}

// Get image base64 string
function getBase64(file, id) {
   var reader = new FileReader();
   reader.readAsDataURL(file);
   reader.onload = function () {
     console.log(reader.result);  
     document.getElementById(id).value = reader.result;
   };
   reader.onerror = function (error) {
     console.log('Error: ', error);
     document.getElementById(id).value = '';
   };
}
// Get image base64 file input
$('#fotopost-inp').change(function(){
    var files = $('#fotopost-inp')[0].files;
    var len = files.length;
    var url = window.URL.createObjectURL(files[0]);
    // Get image
    getBase64(files[0],'postbase64');
    $('#foto img').remove();
    $('#foto').append('<img src="'+url+'">');
    console.log("File: " + files[0].name + ": " + files[0].size + " bytes");
});


/* geolocation is available */
if ("geolocation" in navigator) {       
    function setLocation(lat, lng){ console.log(lat + ' ' + lng); }     
    function geo_success(position) { setLocation(position.coords.latitude, position.coords.longitude); }
    function geo_error() { console.log("Sorry, no position available."); }
    var geo_options = { enableHighAccuracy: true, maximumAge: 30000, timeout: 27000 };
    // Get location
    var wpid = navigator.geolocation.watchPosition(geo_success, geo_error, geo_options);
}
