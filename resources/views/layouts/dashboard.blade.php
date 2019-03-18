@include('includes.head')
<body>
    <div class="page-container page-navigation-top fixed">            
        <div class="page-content" id="_contest_">
             @include('includes.sidebar')
            <ul class="breadcrumb">
                    <li><a href="{{ route('main-dashboard') }}">Home</a></li>
                    <li class="active">@yield('pageTitle')</a></li>
            </ul> 
            <div class="page-title">                    
                    <h2><span class="fa fa-arrow-circle-o-left" onclick="window.history.back()" ></span> @yield('pageTitle')</h2>
            </div>
           
            <div class="page-content-wrap animated slideInUp">      
                       
                @yield('content') 
            </div>
        </div>            
    </div>
      
   
</body>
</html>
@include('includes.footer')   
@include('includes.commanjs')