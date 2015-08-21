<?php
include('../gfunctions.phpp');
$conn = new mysqli(decrypt($_POST[''], decrypt($_POST['']), decrypt($_POST['']), decrypt($_POST['']));

if ($conn->connect_error) {
    echo '<span class="badge bg-danger">Connect Fail</span>';
} else {
    echo '<span class="badge bg-sucsess">Connected</span>';
}
