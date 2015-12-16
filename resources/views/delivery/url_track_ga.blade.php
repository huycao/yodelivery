@if (!empty($data['url_track_ga']))
@foreach($data['url_track_ga'] as $key=>$url)
@if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL) === false)
avlHelperModule.embedTrackingIframe("track_ga_{!! $key !!}", "{!! $url !!}");
@endif
@endforeach
@endif