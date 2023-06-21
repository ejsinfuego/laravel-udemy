<x-profile :sharedData="$sharedData" doctitle="who {{$sharedData['username']}} follows">

  @include('profile-following-only')
   
  
  </x-profile>