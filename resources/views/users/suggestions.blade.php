@extends('layouts.app')

@section('title','Suggestions')

@section('content')
    <p class="fw-bold text-secondary h5 mb-3">Suggestions</p>

    <div class="row  mb-3 gx-5"> 
       
        @forelse( $suggested_users as $user)
            <div class="col-4">
                <div class="card shadow-sm p-3 mb-3">
                    @if($user->avatar)
                        <img src="{{$user->avatar}}" alt="{{$user->name}}" class="rounded-circle avatar-lg mx-auto mb-4">
                    @else
                        <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg mb-4"></i>

                    @endif

                    <a href="{{route('profile.show',$user->id)}}" class="text-decoration-none text-dark text-center mb-3">{{$user->name}}</a>

                    <div class="col-auto mx-auto">
                        @if($user->id != Auth::user()->id)
                            @if(!$user->isFollowed())
                                <form action="{{ route('follow.store', $user->id)}}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary fw-bold  btn-sm">Follow</button>
                                </form>
                             
                            @else
                                <form action="{{ route('follow.destroy', $user->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                        <button class="btn btn-secondary fw-bold btn-sm">
                                        Following
                                        </button>
                                </form>
                            @endif
                        @endif
                    </div>

                    

                </div>
            </div>    
        @empty
            <div class="text-center mt-5">
                <h1 class="h2">No Suggestions yet</h1>
            </div>
        @endforelse
        <div class="d-flex justify-content-center mt-2">
            {{ $suggested_users->links() }}
        </div>
@endsection