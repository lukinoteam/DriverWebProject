function generateLink($type, $data, $name) {
    var url;
    var fileName;
    switch ($type) {
        case 1:
            url = URL.createObjectURL(dataURItoBlob("data:application/msword;base64," + $data));
            fileName = $name + ".doc";
            break;
        case 2:
            url = URL.createObjectURL(dataURItoBlob("data:application/vnd.openxmlformats-officedocument.wordprocessingml.document;base64," + $data));
            fileName = $name + ".docx";
            break;
        case 3:
            url = URL.createObjectURL(dataURItoBlob("data:text/plain;base64," + $data));
            fileName = $name + ".txt";
            break;
        case 4:
            url = URL.createObjectURL(dataURItoBlob("data:application/vnd.ms-excel," + $data));
            fileName = $name + ".xls";
            break;
        case 5:
            url = URL.createObjectURL(dataURItoBlob("data:application/javascript," + $data));
            fileName = $name + ".js";
            break;
        case 6:
            url = URL.createObjectURL(dataURItoBlob("data:application/pdf;base64," + $data));
            fileName = $name + ".pdf";
            break;
        case 7:
            url = URL.createObjectURL(dataURItoBlob("data:image/jpeg;base64," + $data));
            fileName = $name + ".jpg";
            break;
        case 8:
            url = URL.createObjectURL(dataURItoBlob("data:image/png;base64," + $data));
            fileName = $name + ".png";
            break;
        case 9:
            url = URL.createObjectURL(dataURItoBlob("data:image/bmp;base64," + $data));
            fileName = $name + ".bmp";
            break;
        case 10:
            url = URL.createObjectURL(dataURItoBlob("data:text/html;base64," + $data));
            fileName = $name + ".html";
            break;
        case 11:
            url = URL.createObjectURL(dataURItoBlob("data:text/css;base64," + $data));
            fileName = $name + ".css";
            break;
        case 12:
            url = URL.createObjectURL(dataURItoBlob("data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," + $data));
            fileName = $name + ".xlsx";
            break;
        case 14:
            url = URL.createObjectURL(dataURItoBlob("data:audio/mpeg;base64," + $data));
            fileName = $name + ".mp3";
            break;
        default:
            url = URL.createObjectURL(dataURItoBlob("data:application/octet-stream;base64," + $data));
            fileName = $name;
    }
    $("#link").attr({
        "download": fileName,
        "href": url
    }).get(0).click();
}