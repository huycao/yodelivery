<?php 
    $source = preg_replace('/\s\s+/', '', $data->source);
?>
avlInitModule.addSource('{!! $data->id !!}', '{!! addslashes($source) !!}');