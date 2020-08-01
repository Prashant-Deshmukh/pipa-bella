<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Load more data with ajax in laravel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-4 border-bottom pb-1">Product List</h3>
        <div class="row product-list">
            @if(count($products)>0)
                @foreach($products as $data)
                <div class="col-sm-4 mb-3 product-box">
                    <div class="card">
                        <div class="card-body">
                        <h5 class="card-title">{{ $data->id }}. {{ $data->name }}</h5>
                        <p class="card-text">{!! $data->short_description !!}</p>
                        Price: <span class="badge badge-secondary">&#x20b9; {{ $data->price }}</span><br/>
                        In Stock: <span class="badge badge-primary">{{ $data->is_in_stock ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    @if(count($products)>0)
    <p class="text-center mt-4 mb-5"><button class="load-more btn btn-dark" data-totalResult="{{ App\Product::count() }}">Load More</button></p>
    @endif
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script type="text/javascript">
    var main_site="{{ url('/') }}";
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".load-more").on('click',function(){
            var _totalCurrentResult=$(".product-box").length;
            // Ajax Reuqest
            $.ajax({
                url:main_site+'/load-more-data',
                type:'get',
                dataType:'json',
                data:{
                    skip:_totalCurrentResult
                },
                beforeSend:function(){
                    $(".load-more").html('Loading...');
                },
                success:function(response){
                    var _html='';
                    var image="{{ asset('imgs') }}/";
                    $.each(response,function(index,value){
                        _html+='<div class="col-sm-4 mb-3 product-box">';
                            _html+='';
                            _html+='<div class="card">';
                                _html+='<div class="card-body">';
                                    _html+='<h5 class="card-title">'+value.id+'. '+value.name+'</h5>';
                                    _html+='<p class="card-text">'+value.short_description+'</p>';
                                    _html+='Price: <span class="badge badge-secondary">&#x20b9; '+value.price+'</span>';
                                    _html+='<br>In Stock: <span class="badge badge-primary">'+((value.is_in_stock) ? 'Yes' : 'No' )+'</span>';
                                _html+='</div>';
                            _html+='</div>';
                        _html+='</div>';
                    });
                    $(".product-list").append(_html);
                    // Change Load More When No Further result
                    var _totalCurrentResult=$(".product-box").length;
                    var _totalResult=parseInt($(".load-more").attr('data-totalResult'));
                    console.log(_totalCurrentResult);
                    console.log(_totalResult);
                    if(_totalCurrentResult==_totalResult){
                        $(".load-more").remove();
                    }else{
                        $(".load-more").html('Load More');
                    }
                }
            });
        });
    });
</script>
</html>