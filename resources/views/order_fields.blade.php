@foreach ($orders->keys() as $orderKey)
    <input type="hidden" id="{{$orderKey}}" name="{{$orderKey}}" value="{{ $orders->get($orderKey) }}">
@endforeach
