<?php


class SongController extends Controller{
  function index() {
    $page = (int) $_GET['page'] ?? 1;
    $limit = (int) $_GET['limit'] ?? 10;
    $start = (int) $_GET['limit'] ?? 0;
    $albumId = (int) $_GET['albumId'] ?? 0;
    $artistId = (int) $_GET['artistId'] ?? 0;
    $favoris = (int) $_GET['favoris'] ?? 0;
    
  }
  function show() {
    echo __METHOD__;
  }
}