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
