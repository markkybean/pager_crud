function upload_cover_image()
{
    document.getElementById('img_cover_image').src = "./images/progress.gif";
    document.getElementById('img_cover_image').style.width = "31px";
    document.getElementById('img_cover_image').style.height = "31px";
    document.forms.myform.target = 'ifrmupload';
    document.forms.myform.action = 'proc_upload_cover.php';
    document.forms.myform.submit();
    
}

function refresh_cover_image(par_cover_image)
{   
    
    document.getElementById('img_cover_image').src=par_cover_image;
    document.getElementById('cover_image_file').value=par_cover_image;
    document.getElementById('img_cover_image').style.width = "170px";
    document.getElementById('img_cover_image').style.height = "200px";
}