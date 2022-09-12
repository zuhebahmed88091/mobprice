@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
        <div class="outer-wrap section-content-wrap news-page">
            <div class="content-wrap-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-12">

                            <div class="container-area box-panel">
                                <div class="section-title-container row">
                                    <div class="col-12">
                                        <h3 class="section-main-title">
                                            News
                                        </h3>
                                    </div>
                                </div>

                                <div class="section-details-wrapper">
                                    <div class="news-wrap">
                                        @if(count($allNews) == 0)
                                        <div class="col-md-12">
                                            <div class="box">
                                                <div class="box-body">
                                                    <div class="panel-body text-center">
                                                        <h4>No News Available!</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    @foreach($allNews as $news)
                                        <div class="news-items p-0">

                                                <div class="article-card">
                                                <a href="{{route('news.view', $news->id)}}">
                                                    <div class="article-card-thumbnail">
                                                      <img loading="lazy" src="{{ asset('storage/news/' . $news->image) }}" alt="" style="width:100%;"/>
                                                    </div>

                                                    <div class="article-card-content">
                                                        <div>
                                                            <h2 class="article-card-title">{{ $news->title}}</h2>

                                                            <div class="article-card-excerpt">
                                                                <p>
                                                                    {!! $news->short_description !!}
                                                                </p>
                                                            </div>

                                                        </div>

                                                      <div class="article-card-meta">
                                                        <span class="article-card-timestamp"><i class="fa fa-clock"></i>
                                                            {{ $news->created_at}}
                                                        </span>
                                                      </div>
                                                    </div>
                                                </a>
                                                </div>


                                        </div>
                                        @endforeach
                                        @endif
                                    </div>


                                </div>
                                @if ($allNews->total() > 0)
                                    <div class="row" style="margin: 3px 0;">
                                        <div class="col-sm-4" style="margin: 30px 0;">
                                                Showing {{ $allNews->firstItem() }} to {{ $allNews->lastItem() }} of {{ $allNews->total() }}
                                                entries
                                        </div>
                                        <div class="col-sm-8 text-right">
                                                {{ $allNews->links('pagination.default') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>

@endsection

@section('javascript')
    <script>

    </script>
@endsection


