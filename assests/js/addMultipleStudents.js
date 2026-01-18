document.addEventListener("DOMContentLoaded",function(){
    const form=document.getElementById("bulkImportForm");
    const spinner=form.querySelector("#spinner");

    function clearErrors(){
        form.querySelectorAll(".error-message").forEach(el=>el.textContent="");
    }

    form.addEventListener("submit",async function(e){
        e.preventDefault();
        clearErrors();
        spinner.style.display="inline-block";
        const formData=new FormData(form);
        try{
            const res=await fetch("/admin/handlers/addMultipleStudents.php",{method:"POST",body:formData});
            const data=await res.json();
            spinner.style.display="none";
            if(data.status==="success"){
                showToast(data.message,"success");
                if(data.errors && data.errors.length>0){
                    const errorDiv=document.getElementById("csvFileError");
                    errorDiv.innerHTML=data.errors.map(err=>`${err.name} (${err.email} / ${err.reg_no}): ${err.message}`).join("<br>");
                }
                form.reset();
            }else if(data.status==="error"){
                if(data.field){
                    const errorEl=document.getElementById(data.field);
                    if(errorEl) errorEl.textContent=data.message;
                }else{
                    showToast(data.message,"error");
                }
            }
        }catch(err){
            spinner.style.display="none";
            showToast("Something went wrong. Try again.","error");
            console.error(err);
        }
    });
});
