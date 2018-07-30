<?php

function extStrToInt($ext)
{
    switch ($ext) {
        case 'doc':
            return 1;
        case 'docx':
            return 2;
        case 'txt':
            return 3;
        case 'xls':
            return 4;
        case 'js':
            return 5;
        case 'pdf':
            return 6;
        case 'jpg':
        case 'jpeg':
            return 7;
        case 'png':
            return 8;
        case 'bmp':
            return 9;
        case 'html':
        case 'htm':
            return 10;
        case 'css':
            return 11;
        case 'xlsx':
            return 12;
        case 'mp3':
            return 14;
        default:
            return 15;
    }
}
