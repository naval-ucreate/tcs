<!DOCTYPE html>
<html lang="en">
<head>
@include('includes.head')
</head>
<body>
   <div class="page-container">            
        <div class="page-sidebar">
        @include('includes.sidebar')
        </div>
        <div class="page-content"> 
            @include('includes.header') 
            <ul class="breadcrumb">
                <li><a href="#">Home</a></li>                    
                <li class="active">Dashboard</li>
            </ul>  
                
                @yield('content') 
     
        </div>            
    </div>
    @include('includes.footer')     
    @include('includes.footerjs')
</body>
</html>
