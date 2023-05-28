function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object
    parseExcel(files[0]);
}




document.getElementById("studentlist").onchange = (evt) => {
    // (A) NEW FILE READER
    var reader = new FileReader();

    // (B) ON FINISH LOADING 完成載入
    reader.addEventListener("loadend", (evt) => {
        // (B1) GET THE FIRST WORKSHEET 拿到第一個工作表的資料
        var workbook = XLSX.read(evt.target.result, { type: "binary" }),
            worksheet = workbook.Sheets[workbook.SheetNames[0]],
            range = XLSX.utils.decode_range(worksheet["!ref"]);

        workbook.SheetNames.forEach(function (sheetName) {
            // Here is your object
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            json_object = JSON.stringify(XL_row_object);
            try {
                if (localStorage.getItem('studentlist_Obj')) {
                    localStorage.removeItem('studentlist_Obj');
                }
            }
            catch (err) { }
            localStorage.setItem('studentlist_Obj', JSON.stringify(XL_row_object));
            studentlist_preview();
        })





        //   json_object = JSON.stringify(worksheet);
        //   console.log(json_object);

    });

    // (C) START - READ SELECTED EXCEL FILE
    reader.readAsArrayBuffer(evt.target.files[0]);
};



function studentlist_preview() {
    var studentlist = JSON.parse(localStorage.getItem('studentlist_Obj'));
    var str = "";
    var stulist = "[";
    str += '<div class="container precontent">';
    str += '<span class="close">&times;</span>';
    str += '<h3> 預覽學生名單 </h3>';
    str += '<div class="row">';
    str += '<div class="col header">學號</div>';
    str += '<div class="col header">姓名</div>';
    str += '<div class="col header">性別</div>';
    str += '</div>';
    for (var i = 0; i < studentlist.length; i++) {
        if (i % 2 == 0) {
            str += '<div class="row even">';
        }
        else {
            str += '<div class="row odd">';
        }
        if (typeof studentlist[i]['Student_ID'] == 'undefined'||
            typeof studentlist[i]['Student_Name'] == 'undefined'||
            typeof studentlist[i]['Gender'] == 'undefined'){
                alert("格式有誤，請重新選擇檔案。");
                return;

        }
        str += '<div class="col pre">' + studentlist[i]['Student_ID'] + '</div>';
        str += '<div class="col pre">' + studentlist[i]['Student_Name'] + '</div>';
        str += '<div class="col pre">' + studentlist[i]['Gender'] + '</div>';
        str += '</div>';
        stulist +=
            "{" +
            "\"Student_ID\":" + "\"" + studentlist[i]['Student_ID'] + "\"" + "," +
            "\"Student_Name\":" + "\"" + studentlist[i]['Student_Name'] + "\"" + "," +
            "\"Gender\":" + "\"" + studentlist[i]['Gender'] + "\"" +
            "}" + (i == studentlist.length - 1 ? "]" : ",");
    }
    str += '請檢查學生學號、姓名是否正確，學生將以學號登入。<br> ＊若資料有誤，請確認檔案並重新上傳！＊<br>';
    str += '</div>';
    // console.log(studentlist);
    var preview = document.getElementById('preview');
    preview.innerHTML = str;
    preview.style.display = "block";
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        preview.style.display = "none";




        // const acc = document.getElementById("acc");
        const xmlhttp = new XMLHttpRequest();
        // const url = "http://localhost/chippy/tea_register.php?ch=ch&checkacc=" + acc.value;
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                if (this.responseText.trim() != "") {

                }
                var response = this.responseText;
                if (!response.indexOf("ok")) {
                    alert("格式有誤，請重新選擇檔案。");

                }else {

                }
            }
        };
        xmlhttp.open("POST", "checkstulist.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("stulist=" + stulist);
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == preview) {
            preview.style.display = "none";
        }
    }
}