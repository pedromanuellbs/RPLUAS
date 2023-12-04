<html>
  <head>
    <title>CINEMATION | Better Movie Better Life</title>
    @vite('resources/css/app.css')
  </head>
  <body>
    <div class="w-full h-auto min-h-screen flex flex-col">
      <!-- Home Section -->
      @include('header')

      <!-- Sort Section -->
      <div class="ml-28 mt-8 flex flex-row items-center">
        <span class="font-inter font-bold text-xl">Sort</span>
        <div class="relative ml-4">
          <select class="block appearance-none bg-white drop-shadow-[0_0px_4px_rgba(0,0,0,0.25)] text-black font-inter py-3 pl-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white"
            onchange="changeSort(this)">
            <option value="popularity.desc">Popularity (Descending)</option>
            <option value="popularity.asc">Popularity (Ascending)</option>
            <option value="vote_average.desc">Top Rated (Descending)</option>
            <option value="vote_average.asc">Top Rated (Ascending)</option>
          </select>

          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="w-auto pl-28 pr-10 pt-6 pb-10 grid grid-cols-3 lg:grid-cols-5 gap-5" id="dataWrapper">
        @foreach ($tvShows as $tvItem)
        
        @php
        $original_date = $tvItem->first_air_date;
        $timestamp = strtotime($original_date);
        
        $tvYear = date("Y", $timestamp);
        $tvID = $tvItem->id;
        $tvImage = "{$imageBaseURL}/w500{$tvItem->poster_path}";
        $tvName = $tvItem->name;
        $tvRating = $tvItem->vote_average * 10;
        @endphp
        
        <a href="/tv/{{$tvID}}" class="group">
          <div class="min-w-[232px] min-h-[428px] bg-white drop-shadow-[0_0px_8px_rgba(0,0,0,0.25)] group-hover:drop-shadow-[0_0px_8px_rgba(0,0,0,0.5)] rounded-[32px] p-5 flex flex-col duration-100">
            <div class="overflow-hidden rounded-[32px]">
              <img class="w-full h-[300px] rounded-[32px] group-hover:scale-125 duration-200" src="{{$tvImage}}"/>
            </div>
            
            <span class="font-inter font-bold text-xl mt-4 line-clamp-1 group-hover:line-clamp-none">{{$tvName}}</span>
            <span class="font-inter text-sm mt-1">{{$tvYear}}</span>
            <div class="flex flex-row mt-1 items-center">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 21H8V8L15 1L16.25 2.25C16.3667 2.36667 16.4627 2.525 16.538 2.725C16.6127 2.925 16.65 3.11667 16.65 3.3V3.65L15.55 8H21C21.5333 8 22 8.2 22.4 8.6C22.8 9 23 9.46667 23 10V12C23 12.1167 22.9873 12.2417 22.962 12.375C22.9373 12.5083 22.9 12.6333 22.85 12.75L19.85 19.8C19.7 20.1333 19.45 20.4167 19.1 20.65C18.75 20.8833 18.3833 21 18 21ZM6 8V21H2V8H6Z" fill="#38B6FF"/>
              </svg>
              <span class="font-inter text-sm ml-1">{{$tvRating}}%</span>
            </div>
          </div>
        </a>
        @endforeach
      </div>

      <!-- Data Loader -->
      <div class="w-full pl-28 pr-10 flex justify-center mb-5" id="autoLoad">
        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
          x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
          <path fill="#000"
            d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
            <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
              from="0 50 50" to="360 50 50" repeatCount="indefinite" />
          </path>
        </svg>
      </div>

      <!-- Load More -->
      <div class="w-full pl-28 pr-10" id="loadMore">
        <button onclick="loadMore()" class="w-full mb-10 bg-develobe-500 text-white p-4 font-inter font-bold rounded-xl uppercase drop-shadow-lg">Load More</button>
      </div>

      <!-- Error Notification -->
      <div id="notification" class="min-w-[250px] p-4 bg-red-700 text-white text-center rounded-lg fixed z-index-10 top-0 right-0 mr-10 mt-5 drop-shadow-lg">
        <span id="notificationMessage"></span>
      </div>

      <!-- Footer Section -->
      @include('footer')
    </div>

    <script
      src="https://code.jquery.com/jquery-3.6.3.min.js"
      integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
      crossorigin="anonymous"></script>

    <script>
      let baseURL = "<?php echo $baseURL; ?>";
      let imageBaseURL = "<?php echo $imageBaseURL; ?>";
      let apiKey = "<?php echo $apiKey; ?>";
      let sortBy = "<?php echo $sortBy; ?>";
      let page = "<?php echo $page; ?>";
      let minimalVoter = "<?php echo $minimalVoter; ?>";

      // Hide loader
      $('#autoLoad').hide();

      // Hide notification
      $('#notification').hide();

      // Get more data
      function loadMore() {
        $.ajax({
          url: `${baseURL}/discover/tv?page=${++page}&sort_by=${sortBy}&api_key=${apiKey}&vote_count.gte=${minimalVoter}`,
          type: "get", 
          beforeSend: function () {
            // Show loader
            $('#autoLoad').show();
          }
        })
        .done(function (response) {
          // Hide loader
          $('#autoLoad').hide();

          // Get data
          if (response.results){
            var htmlData = [];
            response.results.forEach(item => {
              let original_date = item.first_air_date;
              let date = new Date(original_date);
              let tvYear = date.getFullYear();
              let tvID = item.id;
              let tvImage = `${imageBaseURL}/w500${item.poster_path}`;
              let tvName = item.name;
              let tvRating = item.vote_average * 10;

              htmlData.push(`<a href="/tv/${tvID}" class="group">
                <div class="min-w-[232px] min-h-[428px] bg-white drop-shadow-[0_0px_8px_rgba(0,0,0,0.25)] group-hover:drop-shadow-[0_0px_8px_rgba(0,0,0,0.5)] rounded-[32px] p-5 flex flex-col duration-100">
                  <div class="overflow-hidden rounded-[32px]">
                    <img class="w-full h-[300px] rounded-[32px] group-hover:scale-125 duration-200" src="${tvImage}"/>
                  </div>
                  
                  <span class="font-inter font-bold text-xl mt-4 line-clamp-1 group-hover:line-clamp-none">${tvName}</span>
                  <span class="font-inter text-sm mt-1">${tvYear}</span>
                  <div class="flex flex-row mt-1 items-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M18 21H8V8L15 1L16.25 2.25C16.3667 2.36667 16.4627 2.525 16.538 2.725C16.6127 2.925 16.65 3.11667 16.65 3.3V3.65L15.55 8H21C21.5333 8 22 8.2 22.4 8.6C22.8 9 23 9.46667 23 10V12C23 12.1167 22.9873 12.2417 22.962 12.375C22.9373 12.5083 22.9 12.6333 22.85 12.75L19.85 19.8C19.7 20.1333 19.45 20.4167 19.1 20.65C18.75 20.8833 18.3833 21 18 21ZM6 8V21H2V8H6Z" fill="#38B6FF"/>
                    </svg>
                    <span class="font-inter text-sm ml-1">${tvRating}%</span>
                  </div>
                </div>
              </a>`);
            });

            // Show HTML
            $("#dataWrapper").append(htmlData.join(""));
          }
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
          // Hide loader
          $('#autoLoad').hide();

          // Show notification
          $('#notificationMessage').text('Terjadi kendala, coba beberapa saat lagi');
          $('#notification').show();

          // Set notification timeout. 3 seconds
          setTimeout(function(){ $('#notification').hide(); }, 3000);
        });
      }

      // Sort data
      function changeSort(component){
        if (component.value){
          // Set new value
          sortBy = component.value;

          // Clear data
          $("#dataWrapper").html("");

          // Reset page value to 0 to get first page
          page = 0;

          // Get data
          loadMore();
        }
      }
    </script>

  </body>
</html>