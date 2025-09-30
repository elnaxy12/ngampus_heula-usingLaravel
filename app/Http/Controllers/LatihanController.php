<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LatihanController extends Controller
{
    public function getTable()
    {
        return "
    <style>
        .table-container {
            display: flex;
            justify-content: center; 
            margin-top: 20px;
            width: 100%;
        }
        body {
            max-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;}
        table {
            width: 70%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
    
    <div class='table-container' style='text-align: center;'>
        <table border='1' cellpadding='8' cellspacing='0' style='margin: 0 auto; border-collapse: collapse; width: 600px;'>
            <caption style='text-align: start;'><h1>Data Mahasiswa</h1></caption>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Kelas</th>
            </tr>
            <tr>
                <td>1</td>
                <td>NIM 1</td>
                <td>Nama Lengkap 1</td>
                <td>Kelas 1</td>
            </tr>
            <tr>
                <td>2</td>
                <td>NIM 2</td>
                <td>Nama Lengkap 2</td>
                <td>Kelas 2</td>
            </tr>
        </table>
    </div>
    ";
    }

    public function getForm()
    {
        return "
        
        <style>
            body {
                max-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            input[type=number] {
                -moz-appearance: textfield;
            }
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
         </style>

        <form method='post' action='#' style='width: 300px; margin: 0 auto; border: 1px solid black; padding: 10px;'>
            <label for='nim'>NIM:</label><br>
            <input type='number' id='nim' name='nim' style='width: 100%; margin-bottom: 10px;'><br>

            <label for='nama'>Nama Lengkap:</label><br>
            <input type='text' id='nama' name='nama' style='width: 100%; margin-bottom: 10px;'><br>

            <label for='kelas'>Kelas:</label><br>
            <input type='text' id='kelas' name='kelas' style='width: 100%; margin-bottom: 10px;'><br>

            <button type='submit' style='width: 100px; padding: 8px; cursor: pointer;'>
                Simpan
            </button>
        </form>
    ";
    }
}
