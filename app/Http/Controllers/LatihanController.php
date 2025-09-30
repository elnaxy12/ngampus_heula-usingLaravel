<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LatihanController extends Controller
{
    public function getTable()
    {
        return "
        <div style='text-align: center;'>
            <table border='1' cellpadding='8' cellspacing='0' style='margin: 0 auto; width: 70%; border-collapse: collapse;'>
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
        <form method='post' action='#' style='width: 300px; margin: 0 auto; border: 1px solid black; padding: 10px;'>
            <label for='nim'>NIM:</label><br>
            <input type='text' id='nim' name='nim' style='width: 100%; margin-bottom: 10px;'><br>

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
