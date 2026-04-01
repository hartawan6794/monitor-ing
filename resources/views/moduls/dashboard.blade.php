@extends('layouts.app')

@section('title', 'Administrator')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="main-content">

    <!-- Start::page-header -->
    <div class="md:flex block items-center justify-between my-[1.5rem] page-header-breadcrumb">
      <div>
        <p class="font-semibold text-[1.125rem] text-defaulttextcolor dark:text-defaulttextcolor/70 !mb-0 ">Welcome back, Json Taylor !</p>
        <p class="font-normal text-[#8c9097] dark:text-white/50 text-[0.813rem]">Track your sales activity, leads and deals here.</p>
      </div>
      <div class="btn-list md:mt-0 mt-2">
        <button type="button" class="ti-btn bg-primary text-white btn-wave !font-medium !me-[0.375rem] !ms-0 !text-[0.85rem] !rounded-[0.35rem] !py-[0.51rem] !px-[0.86rem] shadow-none">
          <i class="ri-filter-3-fill  inline-block"></i>Filters
        </button>
        <button type="button" class="ti-btn ti-btn-outline-secondary btn-wave !font-medium  !me-[0.375rem]  !ms-0 !text-[0.85rem] !rounded-[0.35rem] !py-[0.51rem] !px-[0.86rem] shadow-none">
          <i class="ri-upload-cloud-line  inline-block"></i>Export
        </button>
      </div>
    </div>
    <!-- End::page-header -->

    <div class="grid grid-cols-12 gap-x-6">
      <div class="xxl:col-span-9 xl:col-span-12  col-span-12">
        <div class="grid grid-cols-12 gap-x-6">
          <div class="xxl:col-span-4 xl:col-span-4  col-span-12">
            <div class="xxl:col-span-12 xl:col-span-12 col-span-12">
              <div class="box crm-highlight-card">
                <div class="box-body">
                  <div class="flex items-center justify-between">
                    <div>
                      <div class="font-semibold text-[1.125rem] text-white mb-2">Your target is incomplete</div>
                      <span class="block text-[0.75rem] text-white"><span class="opacity-[0.7]">You have
                          completed</span>
                        <span class="font-semibold text-warning">48%</span> <span class="opacity-[0.7]">of the given
                          target, you can also check your status</span>.</span>
                      <span class="block font-semibold mt-[0.125rem]"><a class="text-white text-[0.813rem]" href="javascript:void(0);"><u>Click
                            here</u></a></span>
                    </div>
                    <div>
                      <div id="crm-main" style="min-height: 105.7px;"><div id="apexchartshd38i11z" class="apexcharts-canvas apexchartshd38i11z apexcharts-theme-light" style="width: 100px; height: 105.7px;"><svg id="SvgjsSvg1802" width="100" height="105.7" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="100" height="105.7"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"></div></foreignObject><g id="SvgjsG1804" class="apexcharts-inner apexcharts-graphical" transform="translate(-0.5, 0)"><defs id="SvgjsDefs1803"><clipPath id="gridRectMaskhd38i11z"><rect id="SvgjsRect1805" width="109" height="137" x="-4" y="-6" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskhd38i11z"></clipPath><clipPath id="nonForecastMaskhd38i11z"></clipPath><clipPath id="gridRectMarkerMaskhd38i11z"><rect id="SvgjsRect1806" width="107" height="129" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><g id="SvgjsG1807" class="apexcharts-radialbar"><g id="SvgjsG1808"><g id="SvgjsG1809" class="apexcharts-tracks"><g id="SvgjsG1810" class="apexcharts-radialbar-track apexcharts-track" rel="1"><path id="apexcharts-radialbarTrack-0" d="M 51.5 19.68841463414634 A 31.81158536585366 31.81158536585366 0 1 1 51.49444783097905 19.68841511866449" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="4.806231707317075" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 51.5 19.68841463414634 A 31.81158536585366 31.81158536585366 0 1 1 51.49444783097905 19.68841511866449"></path></g></g><g id="SvgjsG1812"><g id="SvgjsG1816" class="apexcharts-series apexcharts-radial-series" seriesName="Status" rel="1" data:realIndex="0"><path id="SvgjsPath1817" d="M 51.5 19.68841463414634 A 31.81158536585366 31.81158536585366 0 0 1 55.37685702121338 83.07446663248744" fill="none" fill-opacity="0.85" stroke="rgba(255,255,255,0.9)" stroke-opacity="1" stroke-linecap="round" stroke-width="4.95487804878049" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="173" data:value="48" index="0" j="0" data:pathOrig="M 51.5 19.68841463414634 A 31.81158536585366 31.81158536585366 0 0 1 55.37685702121338 83.07446663248744"></path></g><circle id="SvgjsCircle1813" r="29.408469512195122" cx="51.5" cy="51.5" class="apexcharts-radialbar-hollow" fill="#ffffff"></circle><g id="SvgjsG1814" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)" style="opacity: 1;"><text id="SvgjsText1815" font-family="Helvetica, Arial, sans-serif" x="51.5" y="56.5" text-anchor="middle" dominant-baseline="auto" font-size=".875rem" font-weight="600" fill="#4b9bfa" class="apexcharts-text apexcharts-datalabel-value" style="font-family: Helvetica, Arial, sans-serif;">48%</text></g></g></g></g><line id="SvgjsLine1818" x1="0" y1="0" x2="103" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1819" x1="0" y1="0" x2="103" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line></g></svg></div></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="xxl:col-span-12 xl:col-span-12 col-span-12">
              <div class="box">
                <div class="box-header flex justify-between">
                  <div class="box-title">
                    Top Deals
                  </div>
                  <div class="hs-dropdown ti-dropdown">
                    <a aria-label="anchor" href="javascript:void(0);" class="flex items-center justify-center w-[1.75rem] h-[1.75rem]  !text-[0.8rem] !py-1 !px-2 rounded-sm bg-light border-light shadow-none !font-medium" aria-expanded="false">
                      <i class="fe fe-more-vertical text-[0.8rem]"></i>
                    </a>
                    <ul class="hs-dropdown-menu ti-dropdown-menu hidden">
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Week</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Month</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Year</a></li>
                    </ul>
                  </div>
                </div>
                <div class="box-body">
                  <ul class="list-none crm-top-deals mb-0">
                    <li class="mb-[0.9rem]">
                      <div class="flex items-start flex-wrap">
                        <div class="me-2">
                          <span class=" inline-flex items-center justify-center">
                            <img src="../assets/images/faces/10.jpg" alt="" class="w-[1.75rem] h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full">
                          </span>
                        </div>
                        <div class="flex-grow">
                          <p class="font-semibold mb-[1.4px]  text-[0.813rem]">Michael Jordan
                          </p>
                          <p class="text-[#8c9097] dark:text-white/50 text-[0.75rem]">michael.jordan@example.com</p>
                        </div>
                        <div class="font-semibold text-[0.9375rem] ">$2,893</div>
                      </div>
                    </li>
                    <li class="mb-[0.9rem]">
                      <div class="flex items-start flex-wrap">
                        <div class="me-2">
                          <span class="inline-flex items-center justify-center !w-[1.75rem] !h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full text-warning  bg-warning/10 font-semibold">
                            EK
                          </span>
                        </div>
                        <div class="flex-grow">
                          <p class="font-semibold mb-[1.4px]  text-[0.813rem]">Emigo Kiaren</p>
                          <p class="text-[#8c9097] dark:text-white/50 text-[0.75rem]">emigo.kiaren@gmail.com</p>
                        </div>
                        <div class="font-semibold text-[0.9375rem] ">$4,289</div>
                      </div>
                    </li>
                    <li class="mb-[0.9rem]">
                      <div class="flex items-top flex-wrap">
                        <div class="me-2">
                          <span class="inline-flex items-center justify-center">
                            <img src="../assets/images/faces/12.jpg" alt="" class="!w-[1.75rem] !h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full">
                          </span>
                        </div>
                        <div class="flex-grow">
                          <p class="font-semibold mb-[1.4px]  text-[0.813rem]">Randy Origoan
                          </p>
                          <p class="text-[#8c9097] dark:text-white/50 text-[0.75rem]">randy.origoan@gmail.com</p>
                        </div>
                        <div class="font-semibold text-[0.9375rem] ">$6,347</div>
                      </div>
                    </li>
                    <li class="mb-[0.9rem]">
                      <div class="flex items-top flex-wrap">
                        <div class="me-2">
                          <span class="inline-flex items-center justify-center !w-[1.75rem] !h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full text-success bg-success/10 font-semibold">
                            GP
                          </span>
                        </div>
                        <div class="flex-grow">
                          <p class="font-semibold mb-[1.4px]  text-[0.813rem]">George Pieterson
                          </p>
                          <p class="text-[#8c9097] dark:text-white/50 text-[0.75rem]">george.pieterson@gmail.com</p>
                        </div>
                        <div class="font-semibold text-[0.9375rem] ">$3,894</div>
                      </div>
                    </li>
                    <li>
                      <div class="flex items-top flex-wrap">
                        <div class="me-2">
                          <span class="inline-flex items-center justify-center !w-[1.75rem] !h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full text-primary bg-primary/10 font-semibold">
                            KA
                          </span>
                        </div>
                        <div class="flex-grow">
                          <p class="font-semibold mb-[1.4px]  text-[0.813rem]">Kiara Advain</p>
                          <p class="text-[#8c9097] dark:text-white/50 text-[0.75rem]">kiaraadvain214@gmail.com</p>
                        </div>
                        <div class="font-semibold text-[0.9375rem] ">$2,679</div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="xxl:col-span-12 xl:col-span-12 col-span-12">
              <div class="box">
                <div class="box-header justify-between">
                  <div class="box-title">Profit Earned</div>
                  <div class="hs-dropdown ti-dropdown">
                    <a href="javascript:void(0);" class="px-2 font-normal text-[0.75rem] text-[#8c9097] dark:text-white/50" aria-expanded="false">
                      View All<i class="ri-arrow-down-s-line align-middle ms-1 inline-block"></i>
                    </a>
                    <ul class="hs-dropdown-menu ti-dropdown-menu hidden" role="menu">
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Today</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">This Week</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Last Week</a></li>
                    </ul>
                  </div>
                </div>
                <div class="box-body !py-0 !ps-0">
                  <div id="crm-profits-earned" style="min-height: 195px;"><div id="apexchartslkhgip0g" class="apexcharts-canvas apexchartslkhgip0g apexcharts-theme-light" style="width: 370px; height: 180px;"><svg id="SvgjsSvg2169" width="370" height="180" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="370" height="180"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 90px;"></div></foreignObject><g id="SvgjsG2264" class="apexcharts-yaxis" rel="0" transform="translate(15.359375, 0)"><g id="SvgjsG2265" class="apexcharts-yaxis-texts-g"><text id="SvgjsText2267" font-family="Helvetica, Arial, sans-serif" x="20" y="31.5" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2268">100</tspan><title>100</title></text><text id="SvgjsText2270" font-family="Helvetica, Arial, sans-serif" x="20" y="53.9696" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2271">80</tspan><title>80</title></text><text id="SvgjsText2273" font-family="Helvetica, Arial, sans-serif" x="20" y="76.4392" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2274">60</tspan><title>60</title></text><text id="SvgjsText2276" font-family="Helvetica, Arial, sans-serif" x="20" y="98.9088" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2277">40</tspan><title>40</title></text><text id="SvgjsText2279" font-family="Helvetica, Arial, sans-serif" x="20" y="121.3784" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2280">20</tspan><title>20</title></text><text id="SvgjsText2282" font-family="Helvetica, Arial, sans-serif" x="20" y="143.848" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2283">0</tspan><title>0</title></text></g></g><g id="SvgjsG2171" class="apexcharts-inner apexcharts-graphical" transform="translate(45.359375, 30)"><defs id="SvgjsDefs2170"><linearGradient id="SvgjsLinearGradient2174" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop2175" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop2176" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop2177" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMasklkhgip0g"><rect id="SvgjsRect2179" width="320.640625" height="124.34800000000001" x="-4" y="-6" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMasklkhgip0g"></clipPath><clipPath id="nonForecastMasklkhgip0g"></clipPath><clipPath id="gridRectMarkerMasklkhgip0g"><rect id="SvgjsRect2180" width="318.640625" height="116.34800000000001" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect2178" width="13.484598214285716" height="112.34800000000001" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient2174)" class="apexcharts-xcrosshairs" y2="112.34800000000001" filter="none" fill-opacity="0.9"></rect><line id="SvgjsLine2222" x1="0" y1="113.34800000000001" x2="0" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line id="SvgjsLine2223" x1="44.948660714285715" y1="113.34800000000001" x2="44.948660714285715" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line id="SvgjsLine2224" x1="89.89732142857143" y1="113.34800000000001" x2="89.89732142857143" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line id="SvgjsLine2225" x1="134.84598214285714" y1="113.34800000000001" x2="134.84598214285714" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line id="SvgjsLine2226" x1="179.79464285714286" y1="113.34800000000001" x2="179.79464285714286" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line id="SvgjsLine2227" x1="224.74330357142858" y1="113.34800000000001" x2="224.74330357142858" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line id="SvgjsLine2228" x1="269.6919642857143" y1="113.34800000000001" x2="269.6919642857143" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><line id="SvgjsLine2229" x1="314.640625" y1="113.34800000000001" x2="314.640625" y2="119.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-xaxis-tick"></line><g id="SvgjsG2218" class="apexcharts-grid"><g id="SvgjsG2219" class="apexcharts-gridlines-horizontal"><line id="SvgjsLine2231" x1="0" y1="22.469600000000003" x2="314.640625" y2="22.469600000000003" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2232" x1="0" y1="44.93920000000001" x2="314.640625" y2="44.93920000000001" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2233" x1="0" y1="67.40880000000001" x2="314.640625" y2="67.40880000000001" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2234" x1="0" y1="89.87840000000001" x2="314.640625" y2="89.87840000000001" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2220" class="apexcharts-gridlines-vertical"></g><line id="SvgjsLine2237" x1="0" y1="112.34800000000001" x2="314.640625" y2="112.34800000000001" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2236" x1="0" y1="1" x2="0" y2="112.34800000000001" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2221" class="apexcharts-grid-borders"><line id="SvgjsLine2230" x1="0" y1="0" x2="314.640625" y2="0" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2235" x1="0" y1="112.34800000000001" x2="314.640625" y2="112.34800000000001" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2263" x1="0" y1="113.34800000000001" x2="314.640625" y2="113.34800000000001" stroke="rgba(119, 119, 142, 0.05)" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"></line></g><g id="SvgjsG2181" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG2182" class="apexcharts-series" rel="1" seriesName="ProfitxEarned" data:realIndex="0"><path id="SvgjsPath2187" d="M 8.989732142857141 107.34900000000002 L 8.989732142857141 67.91588000000002 C 8.989732142857141 65.41588000000002 11.489732142857141 62.91588000000001 13.989732142857141 62.91588000000001 L 15.474330357142858 62.91588000000001 C 17.974330357142858 62.91588000000001 20.474330357142858 65.41588000000002 20.474330357142858 67.91588000000002 L 20.474330357142858 107.34900000000002 C 20.474330357142858 109.84900000000002 17.974330357142858 112.34900000000002 15.474330357142858 112.34900000000002 L 13.989732142857141 112.34900000000002 C 11.489732142857141 112.34900000000002 8.989732142857141 109.84900000000002 8.989732142857141 107.34900000000002 Z " fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 8.989732142857141 107.34900000000002 L 8.989732142857141 67.91588000000002 C 8.989732142857141 65.41588000000002 11.489732142857141 62.91588000000001 13.989732142857141 62.91588000000001 L 15.474330357142858 62.91588000000001 C 17.974330357142858 62.91588000000001 20.474330357142858 65.41588000000002 20.474330357142858 67.91588000000002 L 20.474330357142858 107.34900000000002 C 20.474330357142858 109.84900000000002 17.974330357142858 112.34900000000002 15.474330357142858 112.34900000000002 L 13.989732142857141 112.34900000000002 C 11.489732142857141 112.34900000000002 8.989732142857141 109.84900000000002 8.989732142857141 107.34900000000002 Z " pathFrom="M 8.989732142857141 107.34900000000002 L 8.989732142857141 67.91588000000002 C 8.989732142857141 65.41588000000002 11.489732142857141 62.91588000000001 13.989732142857141 62.91588000000001 L 15.474330357142858 62.91588000000001 C 17.974330357142858 62.91588000000001 20.474330357142858 65.41588000000002 20.474330357142858 67.91588000000002 L 20.474330357142858 107.34900000000002 C 20.474330357142858 109.84900000000002 17.974330357142858 112.34900000000002 15.474330357142858 112.34900000000002 L 13.989732142857141 112.34900000000002 C 11.489732142857141 112.34900000000002 8.989732142857141 109.84900000000002 8.989732142857141 107.34900000000002 Z  L 8.989732142857141 112.34900000000002 L 20.474330357142858 112.34900000000002 L 20.474330357142858 112.34900000000002 L 20.474330357142858 112.34900000000002 L 20.474330357142858 112.34900000000002 L 20.474330357142858 112.34900000000002 L 8.989732142857141 112.34900000000002 Z" cy="62.91488000000001" cx="52.93839285714286" j="0" val="44" barHeight="49.43312" barWidth="13.484598214285716"></path><path id="SvgjsPath2189" d="M 53.93839285714286 107.34900000000002 L 53.93839285714286 70.16284000000002 C 53.93839285714286 67.66284000000002 56.43839285714286 65.16284000000002 58.93839285714286 65.16284000000002 L 60.42299107142857 65.16284000000002 C 62.92299107142857 65.16284000000002 65.42299107142857 67.66284000000002 65.42299107142857 70.16284000000002 L 65.42299107142857 107.34900000000002 C 65.42299107142857 109.84900000000002 62.92299107142857 112.34900000000002 60.42299107142857 112.34900000000002 L 58.93839285714286 112.34900000000002 C 56.43839285714286 112.34900000000002 53.93839285714286 109.84900000000002 53.93839285714286 107.34900000000002 Z " fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 53.93839285714286 107.34900000000002 L 53.93839285714286 70.16284000000002 C 53.93839285714286 67.66284000000002 56.43839285714286 65.16284000000002 58.93839285714286 65.16284000000002 L 60.42299107142857 65.16284000000002 C 62.92299107142857 65.16284000000002 65.42299107142857 67.66284000000002 65.42299107142857 70.16284000000002 L 65.42299107142857 107.34900000000002 C 65.42299107142857 109.84900000000002 62.92299107142857 112.34900000000002 60.42299107142857 112.34900000000002 L 58.93839285714286 112.34900000000002 C 56.43839285714286 112.34900000000002 53.93839285714286 109.84900000000002 53.93839285714286 107.34900000000002 Z " pathFrom="M 53.93839285714286 107.34900000000002 L 53.93839285714286 70.16284000000002 C 53.93839285714286 67.66284000000002 56.43839285714286 65.16284000000002 58.93839285714286 65.16284000000002 L 60.42299107142857 65.16284000000002 C 62.92299107142857 65.16284000000002 65.42299107142857 67.66284000000002 65.42299107142857 70.16284000000002 L 65.42299107142857 107.34900000000002 C 65.42299107142857 109.84900000000002 62.92299107142857 112.34900000000002 60.42299107142857 112.34900000000002 L 58.93839285714286 112.34900000000002 C 56.43839285714286 112.34900000000002 53.93839285714286 109.84900000000002 53.93839285714286 107.34900000000002 Z  L 53.93839285714286 112.34900000000002 L 65.42299107142857 112.34900000000002 L 65.42299107142857 112.34900000000002 L 65.42299107142857 112.34900000000002 L 65.42299107142857 112.34900000000002 L 65.42299107142857 112.34900000000002 L 53.93839285714286 112.34900000000002 Z" cy="65.16184000000001" cx="97.88705357142857" j="1" val="42" barHeight="47.18616" barWidth="13.484598214285716"></path><path id="SvgjsPath2191" d="M 98.88705357142857 107.34900000000002 L 98.88705357142857 53.310640000000014 C 98.88705357142857 50.810640000000014 101.38705357142857 48.310640000000014 103.88705357142857 48.310640000000014 L 105.37165178571428 48.310640000000014 C 107.87165178571428 48.310640000000014 110.37165178571428 50.810640000000014 110.37165178571428 53.310640000000014 L 110.37165178571428 107.34900000000002 C 110.37165178571428 109.84900000000002 107.87165178571428 112.34900000000002 105.37165178571428 112.34900000000002 L 103.88705357142857 112.34900000000002 C 101.38705357142857 112.34900000000002 98.88705357142857 109.84900000000002 98.88705357142857 107.34900000000002 Z " fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 98.88705357142857 107.34900000000002 L 98.88705357142857 53.310640000000014 C 98.88705357142857 50.810640000000014 101.38705357142857 48.310640000000014 103.88705357142857 48.310640000000014 L 105.37165178571428 48.310640000000014 C 107.87165178571428 48.310640000000014 110.37165178571428 50.810640000000014 110.37165178571428 53.310640000000014 L 110.37165178571428 107.34900000000002 C 110.37165178571428 109.84900000000002 107.87165178571428 112.34900000000002 105.37165178571428 112.34900000000002 L 103.88705357142857 112.34900000000002 C 101.38705357142857 112.34900000000002 98.88705357142857 109.84900000000002 98.88705357142857 107.34900000000002 Z " pathFrom="M 98.88705357142857 107.34900000000002 L 98.88705357142857 53.310640000000014 C 98.88705357142857 50.810640000000014 101.38705357142857 48.310640000000014 103.88705357142857 48.310640000000014 L 105.37165178571428 48.310640000000014 C 107.87165178571428 48.310640000000014 110.37165178571428 50.810640000000014 110.37165178571428 53.310640000000014 L 110.37165178571428 107.34900000000002 C 110.37165178571428 109.84900000000002 107.87165178571428 112.34900000000002 105.37165178571428 112.34900000000002 L 103.88705357142857 112.34900000000002 C 101.38705357142857 112.34900000000002 98.88705357142857 109.84900000000002 98.88705357142857 107.34900000000002 Z  L 98.88705357142857 112.34900000000002 L 110.37165178571428 112.34900000000002 L 110.37165178571428 112.34900000000002 L 110.37165178571428 112.34900000000002 L 110.37165178571428 112.34900000000002 L 110.37165178571428 112.34900000000002 L 98.88705357142857 112.34900000000002 Z" cy="48.309640000000016" cx="142.8357142857143" j="2" val="57" barHeight="64.03836" barWidth="13.484598214285716"></path><path id="SvgjsPath2193" d="M 143.8357142857143 107.34900000000002 L 143.8357142857143 20.729720000000007 C 143.8357142857143 18.229720000000007 146.3357142857143 15.72972000000001 148.8357142857143 15.72972000000001 L 150.3203125 15.72972000000001 C 152.8203125 15.72972000000001 155.3203125 18.229720000000007 155.3203125 20.729720000000007 L 155.3203125 107.34900000000002 C 155.3203125 109.84900000000002 152.8203125 112.34900000000002 150.3203125 112.34900000000002 L 148.8357142857143 112.34900000000002 C 146.3357142857143 112.34900000000002 143.8357142857143 109.84900000000002 143.8357142857143 107.34900000000002 Z " fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 143.8357142857143 107.34900000000002 L 143.8357142857143 20.729720000000007 C 143.8357142857143 18.229720000000007 146.3357142857143 15.72972000000001 148.8357142857143 15.72972000000001 L 150.3203125 15.72972000000001 C 152.8203125 15.72972000000001 155.3203125 18.229720000000007 155.3203125 20.729720000000007 L 155.3203125 107.34900000000002 C 155.3203125 109.84900000000002 152.8203125 112.34900000000002 150.3203125 112.34900000000002 L 148.8357142857143 112.34900000000002 C 146.3357142857143 112.34900000000002 143.8357142857143 109.84900000000002 143.8357142857143 107.34900000000002 Z " pathFrom="M 143.8357142857143 107.34900000000002 L 143.8357142857143 20.729720000000007 C 143.8357142857143 18.229720000000007 146.3357142857143 15.72972000000001 148.8357142857143 15.72972000000001 L 150.3203125 15.72972000000001 C 152.8203125 15.72972000000001 155.3203125 18.229720000000007 155.3203125 20.729720000000007 L 155.3203125 107.34900000000002 C 155.3203125 109.84900000000002 152.8203125 112.34900000000002 150.3203125 112.34900000000002 L 148.8357142857143 112.34900000000002 C 146.3357142857143 112.34900000000002 143.8357142857143 109.84900000000002 143.8357142857143 107.34900000000002 Z  L 143.8357142857143 112.34900000000002 L 155.3203125 112.34900000000002 L 155.3203125 112.34900000000002 L 155.3203125 112.34900000000002 L 155.3203125 112.34900000000002 L 155.3203125 112.34900000000002 L 143.8357142857143 112.34900000000002 Z" cy="15.72872000000001" cx="187.784375" j="3" val="86" barHeight="96.61928" barWidth="13.484598214285716"></path><path id="SvgjsPath2195" d="M 188.784375 107.34900000000002 L 188.784375 52.18716000000001 C 188.784375 49.68716000000001 191.284375 47.18716000000001 193.784375 47.18716000000001 L 195.26897321428572 47.18716000000001 C 197.76897321428572 47.18716000000001 200.26897321428572 49.68716000000001 200.26897321428572 52.18716000000001 L 200.26897321428572 107.34900000000002 C 200.26897321428572 109.84900000000002 197.76897321428572 112.34900000000002 195.26897321428572 112.34900000000002 L 193.784375 112.34900000000002 C 191.284375 112.34900000000002 188.784375 109.84900000000002 188.784375 107.34900000000002 Z " fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 188.784375 107.34900000000002 L 188.784375 52.18716000000001 C 188.784375 49.68716000000001 191.284375 47.18716000000001 193.784375 47.18716000000001 L 195.26897321428572 47.18716000000001 C 197.76897321428572 47.18716000000001 200.26897321428572 49.68716000000001 200.26897321428572 52.18716000000001 L 200.26897321428572 107.34900000000002 C 200.26897321428572 109.84900000000002 197.76897321428572 112.34900000000002 195.26897321428572 112.34900000000002 L 193.784375 112.34900000000002 C 191.284375 112.34900000000002 188.784375 109.84900000000002 188.784375 107.34900000000002 Z " pathFrom="M 188.784375 107.34900000000002 L 188.784375 52.18716000000001 C 188.784375 49.68716000000001 191.284375 47.18716000000001 193.784375 47.18716000000001 L 195.26897321428572 47.18716000000001 C 197.76897321428572 47.18716000000001 200.26897321428572 49.68716000000001 200.26897321428572 52.18716000000001 L 200.26897321428572 107.34900000000002 C 200.26897321428572 109.84900000000002 197.76897321428572 112.34900000000002 195.26897321428572 112.34900000000002 L 193.784375 112.34900000000002 C 191.284375 112.34900000000002 188.784375 109.84900000000002 188.784375 107.34900000000002 Z  L 188.784375 112.34900000000002 L 200.26897321428572 112.34900000000002 L 200.26897321428572 112.34900000000002 L 200.26897321428572 112.34900000000002 L 200.26897321428572 112.34900000000002 L 200.26897321428572 112.34900000000002 L 188.784375 112.34900000000002 Z" cy="47.186160000000015" cx="232.73303571428573" j="4" val="58" barHeight="65.16184" barWidth="13.484598214285716"></path><path id="SvgjsPath2197" d="M 233.73303571428573 107.34900000000002 L 233.73303571428573 55.55760000000001 C 233.73303571428573 53.05760000000001 236.23303571428573 50.55760000000001 238.73303571428573 50.55760000000001 L 240.21763392857144 50.55760000000001 C 242.71763392857144 50.55760000000001 245.21763392857144 53.05760000000001 245.21763392857144 55.55760000000001 L 245.21763392857144 107.34900000000002 C 245.21763392857144 109.84900000000002 242.71763392857144 112.34900000000002 240.21763392857144 112.34900000000002 L 238.73303571428573 112.34900000000002 C 236.23303571428573 112.34900000000002 233.73303571428573 109.84900000000002 233.73303571428573 107.34900000000002 Z " fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 233.73303571428573 107.34900000000002 L 233.73303571428573 55.55760000000001 C 233.73303571428573 53.05760000000001 236.23303571428573 50.55760000000001 238.73303571428573 50.55760000000001 L 240.21763392857144 50.55760000000001 C 242.71763392857144 50.55760000000001 245.21763392857144 53.05760000000001 245.21763392857144 55.55760000000001 L 245.21763392857144 107.34900000000002 C 245.21763392857144 109.84900000000002 242.71763392857144 112.34900000000002 240.21763392857144 112.34900000000002 L 238.73303571428573 112.34900000000002 C 236.23303571428573 112.34900000000002 233.73303571428573 109.84900000000002 233.73303571428573 107.34900000000002 Z " pathFrom="M 233.73303571428573 107.34900000000002 L 233.73303571428573 55.55760000000001 C 233.73303571428573 53.05760000000001 236.23303571428573 50.55760000000001 238.73303571428573 50.55760000000001 L 240.21763392857144 50.55760000000001 C 242.71763392857144 50.55760000000001 245.21763392857144 53.05760000000001 245.21763392857144 55.55760000000001 L 245.21763392857144 107.34900000000002 C 245.21763392857144 109.84900000000002 242.71763392857144 112.34900000000002 240.21763392857144 112.34900000000002 L 238.73303571428573 112.34900000000002 C 236.23303571428573 112.34900000000002 233.73303571428573 109.84900000000002 233.73303571428573 107.34900000000002 Z  L 233.73303571428573 112.34900000000002 L 245.21763392857144 112.34900000000002 L 245.21763392857144 112.34900000000002 L 245.21763392857144 112.34900000000002 L 245.21763392857144 112.34900000000002 L 245.21763392857144 112.34900000000002 L 233.73303571428573 112.34900000000002 Z" cy="50.55660000000001" cx="277.68169642857146" j="5" val="55" barHeight="61.7914" barWidth="13.484598214285716"></path><path id="SvgjsPath2199" d="M 278.68169642857146 107.34900000000002 L 278.68169642857146 38.705400000000004 C 278.68169642857146 36.205400000000004 281.18169642857146 33.705400000000004 283.68169642857146 33.705400000000004 L 285.16629464285717 33.705400000000004 C 287.66629464285717 33.705400000000004 290.16629464285717 36.205400000000004 290.16629464285717 38.705400000000004 L 290.16629464285717 107.34900000000002 C 290.16629464285717 109.84900000000002 287.66629464285717 112.34900000000002 285.16629464285717 112.34900000000002 L 283.68169642857146 112.34900000000002 C 281.18169642857146 112.34900000000002 278.68169642857146 109.84900000000002 278.68169642857146 107.34900000000002 Z " fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 278.68169642857146 107.34900000000002 L 278.68169642857146 38.705400000000004 C 278.68169642857146 36.205400000000004 281.18169642857146 33.705400000000004 283.68169642857146 33.705400000000004 L 285.16629464285717 33.705400000000004 C 287.66629464285717 33.705400000000004 290.16629464285717 36.205400000000004 290.16629464285717 38.705400000000004 L 290.16629464285717 107.34900000000002 C 290.16629464285717 109.84900000000002 287.66629464285717 112.34900000000002 285.16629464285717 112.34900000000002 L 283.68169642857146 112.34900000000002 C 281.18169642857146 112.34900000000002 278.68169642857146 109.84900000000002 278.68169642857146 107.34900000000002 Z " pathFrom="M 278.68169642857146 107.34900000000002 L 278.68169642857146 38.705400000000004 C 278.68169642857146 36.205400000000004 281.18169642857146 33.705400000000004 283.68169642857146 33.705400000000004 L 285.16629464285717 33.705400000000004 C 287.66629464285717 33.705400000000004 290.16629464285717 36.205400000000004 290.16629464285717 38.705400000000004 L 290.16629464285717 107.34900000000002 C 290.16629464285717 109.84900000000002 287.66629464285717 112.34900000000002 285.16629464285717 112.34900000000002 L 283.68169642857146 112.34900000000002 C 281.18169642857146 112.34900000000002 278.68169642857146 109.84900000000002 278.68169642857146 107.34900000000002 Z  L 278.68169642857146 112.34900000000002 L 290.16629464285717 112.34900000000002 L 290.16629464285717 112.34900000000002 L 290.16629464285717 112.34900000000002 L 290.16629464285717 112.34900000000002 L 290.16629464285717 112.34900000000002 L 278.68169642857146 112.34900000000002 Z" cy="33.70440000000001" cx="322.6303571428572" j="6" val="70" barHeight="78.6436" barWidth="13.484598214285716"></path><g id="SvgjsG2184" class="apexcharts-bar-goals-markers"><g id="SvgjsG2186" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2188" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2190" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2192" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2194" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2196" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2198" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g></g><g id="SvgjsG2185" class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g></g><g id="SvgjsG2200" class="apexcharts-series" rel="2" seriesName="TotalxSales" data:realIndex="1"><path id="SvgjsPath2205" d="M 22.474330357142858 107.34900000000002 L 22.474330357142858 79.15068000000002 C 22.474330357142858 76.65068000000002 24.974330357142858 74.15068000000002 27.474330357142858 74.15068000000002 L 28.958928571428572 74.15068000000002 C 31.458928571428572 74.15068000000002 33.95892857142857 76.65068000000002 33.95892857142857 79.15068000000002 L 33.95892857142857 107.34900000000002 C 33.95892857142857 109.84900000000002 31.458928571428572 112.34900000000002 28.958928571428572 112.34900000000002 L 27.474330357142858 112.34900000000002 C 24.974330357142858 112.34900000000002 22.474330357142858 109.84900000000002 22.474330357142858 107.34900000000002 Z " fill="rgba(237,237,237,0.85)" fill-opacity="1" stroke="#ededed" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 22.474330357142858 107.34900000000002 L 22.474330357142858 79.15068000000002 C 22.474330357142858 76.65068000000002 24.974330357142858 74.15068000000002 27.474330357142858 74.15068000000002 L 28.958928571428572 74.15068000000002 C 31.458928571428572 74.15068000000002 33.95892857142857 76.65068000000002 33.95892857142857 79.15068000000002 L 33.95892857142857 107.34900000000002 C 33.95892857142857 109.84900000000002 31.458928571428572 112.34900000000002 28.958928571428572 112.34900000000002 L 27.474330357142858 112.34900000000002 C 24.974330357142858 112.34900000000002 22.474330357142858 109.84900000000002 22.474330357142858 107.34900000000002 Z " pathFrom="M 22.474330357142858 107.34900000000002 L 22.474330357142858 79.15068000000002 C 22.474330357142858 76.65068000000002 24.974330357142858 74.15068000000002 27.474330357142858 74.15068000000002 L 28.958928571428572 74.15068000000002 C 31.458928571428572 74.15068000000002 33.95892857142857 76.65068000000002 33.95892857142857 79.15068000000002 L 33.95892857142857 107.34900000000002 C 33.95892857142857 109.84900000000002 31.458928571428572 112.34900000000002 28.958928571428572 112.34900000000002 L 27.474330357142858 112.34900000000002 C 24.974330357142858 112.34900000000002 22.474330357142858 109.84900000000002 22.474330357142858 107.34900000000002 Z  L 22.474330357142858 112.34900000000002 L 33.95892857142857 112.34900000000002 L 33.95892857142857 112.34900000000002 L 33.95892857142857 112.34900000000002 L 33.95892857142857 112.34900000000002 L 33.95892857142857 112.34900000000002 L 22.474330357142858 112.34900000000002 Z" cy="74.14968000000002" cx="66.42299107142857" j="0" val="34" barHeight="38.19832" barWidth="13.484598214285716"></path><path id="SvgjsPath2207" d="M 67.42299107142857 107.34900000000002 L 67.42299107142857 92.63244000000002 C 67.42299107142857 90.13244000000002 69.92299107142857 87.63244000000002 72.42299107142857 87.63244000000002 L 73.90758928571428 87.63244000000002 C 76.40758928571428 87.63244000000002 78.90758928571428 90.13244000000002 78.90758928571428 92.63244000000002 L 78.90758928571428 107.34900000000002 C 78.90758928571428 109.84900000000002 76.40758928571428 112.34900000000002 73.90758928571428 112.34900000000002 L 72.42299107142857 112.34900000000002 C 69.92299107142857 112.34900000000002 67.42299107142857 109.84900000000002 67.42299107142857 107.34900000000002 Z " fill="rgba(237,237,237,0.85)" fill-opacity="1" stroke="#ededed" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 67.42299107142857 107.34900000000002 L 67.42299107142857 92.63244000000002 C 67.42299107142857 90.13244000000002 69.92299107142857 87.63244000000002 72.42299107142857 87.63244000000002 L 73.90758928571428 87.63244000000002 C 76.40758928571428 87.63244000000002 78.90758928571428 90.13244000000002 78.90758928571428 92.63244000000002 L 78.90758928571428 107.34900000000002 C 78.90758928571428 109.84900000000002 76.40758928571428 112.34900000000002 73.90758928571428 112.34900000000002 L 72.42299107142857 112.34900000000002 C 69.92299107142857 112.34900000000002 67.42299107142857 109.84900000000002 67.42299107142857 107.34900000000002 Z " pathFrom="M 67.42299107142857 107.34900000000002 L 67.42299107142857 92.63244000000002 C 67.42299107142857 90.13244000000002 69.92299107142857 87.63244000000002 72.42299107142857 87.63244000000002 L 73.90758928571428 87.63244000000002 C 76.40758928571428 87.63244000000002 78.90758928571428 90.13244000000002 78.90758928571428 92.63244000000002 L 78.90758928571428 107.34900000000002 C 78.90758928571428 109.84900000000002 76.40758928571428 112.34900000000002 73.90758928571428 112.34900000000002 L 72.42299107142857 112.34900000000002 C 69.92299107142857 112.34900000000002 67.42299107142857 109.84900000000002 67.42299107142857 107.34900000000002 Z  L 67.42299107142857 112.34900000000002 L 78.90758928571428 112.34900000000002 L 78.90758928571428 112.34900000000002 L 78.90758928571428 112.34900000000002 L 78.90758928571428 112.34900000000002 L 78.90758928571428 112.34900000000002 L 67.42299107142857 112.34900000000002 Z" cy="87.63144000000001" cx="111.37165178571428" j="1" val="22" barHeight="24.71656" barWidth="13.484598214285716"></path><path id="SvgjsPath2209" d="M 112.37165178571428 107.34900000000002 L 112.37165178571428 75.78024000000002 C 112.37165178571428 73.28024000000002 114.87165178571428 70.78024000000002 117.37165178571428 70.78024000000002 L 118.85624999999999 70.78024000000002 C 121.35624999999999 70.78024000000002 123.85624999999999 73.28024000000002 123.85624999999999 75.78024000000002 L 123.85624999999999 107.34900000000002 C 123.85624999999999 109.84900000000002 121.35624999999999 112.34900000000002 118.85624999999999 112.34900000000002 L 117.37165178571428 112.34900000000002 C 114.87165178571428 112.34900000000002 112.37165178571428 109.84900000000002 112.37165178571428 107.34900000000002 Z " fill="rgba(237,237,237,0.85)" fill-opacity="1" stroke="#ededed" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 112.37165178571428 107.34900000000002 L 112.37165178571428 75.78024000000002 C 112.37165178571428 73.28024000000002 114.87165178571428 70.78024000000002 117.37165178571428 70.78024000000002 L 118.85624999999999 70.78024000000002 C 121.35624999999999 70.78024000000002 123.85624999999999 73.28024000000002 123.85624999999999 75.78024000000002 L 123.85624999999999 107.34900000000002 C 123.85624999999999 109.84900000000002 121.35624999999999 112.34900000000002 118.85624999999999 112.34900000000002 L 117.37165178571428 112.34900000000002 C 114.87165178571428 112.34900000000002 112.37165178571428 109.84900000000002 112.37165178571428 107.34900000000002 Z " pathFrom="M 112.37165178571428 107.34900000000002 L 112.37165178571428 75.78024000000002 C 112.37165178571428 73.28024000000002 114.87165178571428 70.78024000000002 117.37165178571428 70.78024000000002 L 118.85624999999999 70.78024000000002 C 121.35624999999999 70.78024000000002 123.85624999999999 73.28024000000002 123.85624999999999 75.78024000000002 L 123.85624999999999 107.34900000000002 C 123.85624999999999 109.84900000000002 121.35624999999999 112.34900000000002 118.85624999999999 112.34900000000002 L 117.37165178571428 112.34900000000002 C 114.87165178571428 112.34900000000002 112.37165178571428 109.84900000000002 112.37165178571428 107.34900000000002 Z  L 112.37165178571428 112.34900000000002 L 123.85624999999999 112.34900000000002 L 123.85624999999999 112.34900000000002 L 123.85624999999999 112.34900000000002 L 123.85624999999999 112.34900000000002 L 123.85624999999999 112.34900000000002 L 112.37165178571428 112.34900000000002 Z" cy="70.77924000000002" cx="156.3203125" j="2" val="37" barHeight="41.568760000000005" barWidth="13.484598214285716"></path><path id="SvgjsPath2211" d="M 157.3203125 107.34900000000002 L 157.3203125 54.43412000000001 C 157.3203125 51.93412000000001 159.8203125 49.43412000000001 162.3203125 49.43412000000001 L 163.8049107142857 49.43412000000001 C 166.3049107142857 49.43412000000001 168.8049107142857 51.93412000000001 168.8049107142857 54.43412000000001 L 168.8049107142857 107.34900000000002 C 168.8049107142857 109.84900000000002 166.3049107142857 112.34900000000002 163.8049107142857 112.34900000000002 L 162.3203125 112.34900000000002 C 159.8203125 112.34900000000002 157.3203125 109.84900000000002 157.3203125 107.34900000000002 Z " fill="rgba(237,237,237,0.85)" fill-opacity="1" stroke="#ededed" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 157.3203125 107.34900000000002 L 157.3203125 54.43412000000001 C 157.3203125 51.93412000000001 159.8203125 49.43412000000001 162.3203125 49.43412000000001 L 163.8049107142857 49.43412000000001 C 166.3049107142857 49.43412000000001 168.8049107142857 51.93412000000001 168.8049107142857 54.43412000000001 L 168.8049107142857 107.34900000000002 C 168.8049107142857 109.84900000000002 166.3049107142857 112.34900000000002 163.8049107142857 112.34900000000002 L 162.3203125 112.34900000000002 C 159.8203125 112.34900000000002 157.3203125 109.84900000000002 157.3203125 107.34900000000002 Z " pathFrom="M 157.3203125 107.34900000000002 L 157.3203125 54.43412000000001 C 157.3203125 51.93412000000001 159.8203125 49.43412000000001 162.3203125 49.43412000000001 L 163.8049107142857 49.43412000000001 C 166.3049107142857 49.43412000000001 168.8049107142857 51.93412000000001 168.8049107142857 54.43412000000001 L 168.8049107142857 107.34900000000002 C 168.8049107142857 109.84900000000002 166.3049107142857 112.34900000000002 163.8049107142857 112.34900000000002 L 162.3203125 112.34900000000002 C 159.8203125 112.34900000000002 157.3203125 109.84900000000002 157.3203125 107.34900000000002 Z  L 157.3203125 112.34900000000002 L 168.8049107142857 112.34900000000002 L 168.8049107142857 112.34900000000002 L 168.8049107142857 112.34900000000002 L 168.8049107142857 112.34900000000002 L 168.8049107142857 112.34900000000002 L 157.3203125 112.34900000000002 Z" cy="49.43312000000001" cx="201.26897321428572" j="3" val="56" barHeight="62.914880000000004" barWidth="13.484598214285716"></path><path id="SvgjsPath2213" d="M 202.26897321428572 107.34900000000002 L 202.26897321428572 93.75592000000002 C 202.26897321428572 91.25592000000002 204.76897321428572 88.75592000000002 207.26897321428572 88.75592000000002 L 208.75357142857143 88.75592000000002 C 211.25357142857143 88.75592000000002 213.75357142857143 91.25592000000002 213.75357142857143 93.75592000000002 L 213.75357142857143 107.34900000000002 C 213.75357142857143 109.84900000000002 211.25357142857143 112.34900000000002 208.75357142857143 112.34900000000002 L 207.26897321428572 112.34900000000002 C 204.76897321428572 112.34900000000002 202.26897321428572 109.84900000000002 202.26897321428572 107.34900000000002 Z " fill="rgba(237,237,237,0.85)" fill-opacity="1" stroke="#ededed" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 202.26897321428572 107.34900000000002 L 202.26897321428572 93.75592000000002 C 202.26897321428572 91.25592000000002 204.76897321428572 88.75592000000002 207.26897321428572 88.75592000000002 L 208.75357142857143 88.75592000000002 C 211.25357142857143 88.75592000000002 213.75357142857143 91.25592000000002 213.75357142857143 93.75592000000002 L 213.75357142857143 107.34900000000002 C 213.75357142857143 109.84900000000002 211.25357142857143 112.34900000000002 208.75357142857143 112.34900000000002 L 207.26897321428572 112.34900000000002 C 204.76897321428572 112.34900000000002 202.26897321428572 109.84900000000002 202.26897321428572 107.34900000000002 Z " pathFrom="M 202.26897321428572 107.34900000000002 L 202.26897321428572 93.75592000000002 C 202.26897321428572 91.25592000000002 204.76897321428572 88.75592000000002 207.26897321428572 88.75592000000002 L 208.75357142857143 88.75592000000002 C 211.25357142857143 88.75592000000002 213.75357142857143 91.25592000000002 213.75357142857143 93.75592000000002 L 213.75357142857143 107.34900000000002 C 213.75357142857143 109.84900000000002 211.25357142857143 112.34900000000002 208.75357142857143 112.34900000000002 L 207.26897321428572 112.34900000000002 C 204.76897321428572 112.34900000000002 202.26897321428572 109.84900000000002 202.26897321428572 107.34900000000002 Z  L 202.26897321428572 112.34900000000002 L 213.75357142857143 112.34900000000002 L 213.75357142857143 112.34900000000002 L 213.75357142857143 112.34900000000002 L 213.75357142857143 112.34900000000002 L 213.75357142857143 112.34900000000002 L 202.26897321428572 112.34900000000002 Z" cy="88.75492000000001" cx="246.21763392857144" j="4" val="21" barHeight="23.59308" barWidth="13.484598214285716"></path><path id="SvgjsPath2215" d="M 247.21763392857144 107.34900000000002 L 247.21763392857144 78.02720000000002 C 247.21763392857144 75.52720000000002 249.71763392857144 73.02720000000002 252.21763392857144 73.02720000000002 L 253.70223214285716 73.02720000000002 C 256.20223214285716 73.02720000000002 258.70223214285716 75.52720000000002 258.70223214285716 78.02720000000002 L 258.70223214285716 107.34900000000002 C 258.70223214285716 109.84900000000002 256.20223214285716 112.34900000000002 253.70223214285716 112.34900000000002 L 252.21763392857144 112.34900000000002 C 249.71763392857144 112.34900000000002 247.21763392857144 109.84900000000002 247.21763392857144 107.34900000000002 Z " fill="rgba(237,237,237,0.85)" fill-opacity="1" stroke="#ededed" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 247.21763392857144 107.34900000000002 L 247.21763392857144 78.02720000000002 C 247.21763392857144 75.52720000000002 249.71763392857144 73.02720000000002 252.21763392857144 73.02720000000002 L 253.70223214285716 73.02720000000002 C 256.20223214285716 73.02720000000002 258.70223214285716 75.52720000000002 258.70223214285716 78.02720000000002 L 258.70223214285716 107.34900000000002 C 258.70223214285716 109.84900000000002 256.20223214285716 112.34900000000002 253.70223214285716 112.34900000000002 L 252.21763392857144 112.34900000000002 C 249.71763392857144 112.34900000000002 247.21763392857144 109.84900000000002 247.21763392857144 107.34900000000002 Z " pathFrom="M 247.21763392857144 107.34900000000002 L 247.21763392857144 78.02720000000002 C 247.21763392857144 75.52720000000002 249.71763392857144 73.02720000000002 252.21763392857144 73.02720000000002 L 253.70223214285716 73.02720000000002 C 256.20223214285716 73.02720000000002 258.70223214285716 75.52720000000002 258.70223214285716 78.02720000000002 L 258.70223214285716 107.34900000000002 C 258.70223214285716 109.84900000000002 256.20223214285716 112.34900000000002 253.70223214285716 112.34900000000002 L 252.21763392857144 112.34900000000002 C 249.71763392857144 112.34900000000002 247.21763392857144 109.84900000000002 247.21763392857144 107.34900000000002 Z  L 247.21763392857144 112.34900000000002 L 258.70223214285716 112.34900000000002 L 258.70223214285716 112.34900000000002 L 258.70223214285716 112.34900000000002 L 258.70223214285716 112.34900000000002 L 258.70223214285716 112.34900000000002 L 247.21763392857144 112.34900000000002 Z" cy="73.02620000000002" cx="291.16629464285717" j="5" val="35" barHeight="39.3218" barWidth="13.484598214285716"></path><path id="SvgjsPath2217" d="M 292.16629464285717 107.34900000000002 L 292.16629464285717 49.94020000000001 C 292.16629464285717 47.44020000000001 294.66629464285717 44.94020000000001 297.16629464285717 44.94020000000001 L 298.6508928571429 44.94020000000001 C 301.1508928571429 44.94020000000001 303.6508928571429 47.44020000000001 303.6508928571429 49.94020000000001 L 303.6508928571429 107.34900000000002 C 303.6508928571429 109.84900000000002 301.1508928571429 112.34900000000002 298.6508928571429 112.34900000000002 L 297.16629464285717 112.34900000000002 C 294.66629464285717 112.34900000000002 292.16629464285717 109.84900000000002 292.16629464285717 107.34900000000002 Z " fill="rgba(237,237,237,0.85)" fill-opacity="1" stroke="#ededed" stroke-opacity="1" stroke-linecap="round" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMasklkhgip0g)" pathTo="M 292.16629464285717 107.34900000000002 L 292.16629464285717 49.94020000000001 C 292.16629464285717 47.44020000000001 294.66629464285717 44.94020000000001 297.16629464285717 44.94020000000001 L 298.6508928571429 44.94020000000001 C 301.1508928571429 44.94020000000001 303.6508928571429 47.44020000000001 303.6508928571429 49.94020000000001 L 303.6508928571429 107.34900000000002 C 303.6508928571429 109.84900000000002 301.1508928571429 112.34900000000002 298.6508928571429 112.34900000000002 L 297.16629464285717 112.34900000000002 C 294.66629464285717 112.34900000000002 292.16629464285717 109.84900000000002 292.16629464285717 107.34900000000002 Z " pathFrom="M 292.16629464285717 107.34900000000002 L 292.16629464285717 49.94020000000001 C 292.16629464285717 47.44020000000001 294.66629464285717 44.94020000000001 297.16629464285717 44.94020000000001 L 298.6508928571429 44.94020000000001 C 301.1508928571429 44.94020000000001 303.6508928571429 47.44020000000001 303.6508928571429 49.94020000000001 L 303.6508928571429 107.34900000000002 C 303.6508928571429 109.84900000000002 301.1508928571429 112.34900000000002 298.6508928571429 112.34900000000002 L 297.16629464285717 112.34900000000002 C 294.66629464285717 112.34900000000002 292.16629464285717 109.84900000000002 292.16629464285717 107.34900000000002 Z  L 292.16629464285717 112.34900000000002 L 303.6508928571429 112.34900000000002 L 303.6508928571429 112.34900000000002 L 303.6508928571429 112.34900000000002 L 303.6508928571429 112.34900000000002 L 303.6508928571429 112.34900000000002 L 292.16629464285717 112.34900000000002 Z" cy="44.939200000000014" cx="336.1149553571429" j="6" val="60" barHeight="67.4088" barWidth="13.484598214285716"></path><g id="SvgjsG2202" class="apexcharts-bar-goals-markers"><g id="SvgjsG2204" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2206" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2208" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2210" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2212" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2214" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g><g id="SvgjsG2216" className="apexcharts-bar-goals-groups" class="apexcharts-hidden-element-shown" clip-path="url(#gridRectMarkerMasklkhgip0g)"></g></g><g id="SvgjsG2203" class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g></g><g id="SvgjsG2183" class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realIndex="0"></g><g id="SvgjsG2201" class="apexcharts-datalabels apexcharts-hidden-element-shown" data:realIndex="1"></g></g><line id="SvgjsLine2238" x1="0" y1="0" x2="314.640625" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2239" x1="0" y1="0" x2="314.640625" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2240" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2241" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText2243" font-family="Helvetica, Arial, sans-serif" x="22.474330357142858" y="141.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2244">S</tspan><title>S</title></text><text id="SvgjsText2246" font-family="Helvetica, Arial, sans-serif" x="67.42299107142857" y="141.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2247">M</tspan><title>M</title></text><text id="SvgjsText2249" font-family="Helvetica, Arial, sans-serif" x="112.37165178571428" y="141.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2250">T</tspan><title>T</title></text><text id="SvgjsText2252" font-family="Helvetica, Arial, sans-serif" x="157.3203125" y="141.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2253">W</tspan><title>W</title></text><text id="SvgjsText2255" font-family="Helvetica, Arial, sans-serif" x="202.26897321428572" y="141.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2256">T</tspan><title>T</title></text><text id="SvgjsText2258" font-family="Helvetica, Arial, sans-serif" x="247.21763392857142" y="141.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2259">F</tspan><title>F</title></text><text id="SvgjsText2261" font-family="Helvetica, Arial, sans-serif" x="292.16629464285717" y="141.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2262">S</tspan><title>S</title></text></g></g><g id="SvgjsG2284" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2285" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2286" class="apexcharts-point-annotations"></g></g></svg><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(132, 90, 223);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 2;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(237, 237, 237);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
                </div>
              </div>
            </div>
          </div>
          <div class="xxl:col-span-8  xl:col-span-8  col-span-12">
            <div class="grid grid-cols-12 gap-x-6">
              <div class="xxl:col-span-6 xl:col-span-6 col-span-12">
                <div class="box overflow-hidden">
                  <div class="box-body">
                    <div class="flex items-top justify-between">
                      <div>
                        <span class="!text-[0.8rem]  !w-[2.5rem] !h-[2.5rem] !leading-[2.5rem] !rounded-full inline-flex items-center justify-center bg-primary">
                          <i class="ti ti-users text-[1rem] text-white"></i>
                        </span>
                      </div>
                      <div class="flex-grow ms-4">
                        <div class="flex items-center justify-between flex-wrap">
                          <div>
                            <p class="text-[#8c9097] dark:text-white/50 text-[0.813rem] mb-0">Total Customers</p>
                            <h4 class="font-semibold  text-[1.5rem] !mb-2 ">1,02,890</h4>
                          </div>
                          <div id="crm-total-customers" style="min-height: 40px;"><div id="apexcharts98hf3ov6" class="apexcharts-canvas apexcharts98hf3ov6 apexcharts-theme-light" style="width: 100px; height: 40px;"><svg id="SvgjsSvg1820" width="100" height="40" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="100" height="40"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 20px;"></div></foreignObject><rect id="SvgjsRect1824" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1867" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1822" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0.75)"><defs id="SvgjsDefs1821"><clipPath id="gridRectMask98hf3ov6"><rect id="SvgjsRect1826" width="105.5" height="48.5" x="-3.5" y="-5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask98hf3ov6"></clipPath><clipPath id="nonForecastMask98hf3ov6"></clipPath><clipPath id="gridRectMarkerMask98hf3ov6"><rect id="SvgjsRect1827" width="104" height="42.5" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient1832" x1="0" y1="1" x2="1" y2="1"><stop id="SvgjsStop1833" stop-opacity="0.9" stop-color="rgba(66,45,112,0.9)" offset="0"></stop><stop id="SvgjsStop1834" stop-opacity="0.9" stop-color="rgba(132,90,223,0.9)" offset="0.98"></stop><stop id="SvgjsStop1835" stop-opacity="0.9" stop-color="rgba(132,90,223,0.9)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine1825" x1="0" y1="0" x2="0" y2="38.5" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="38.5" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1837" class="apexcharts-grid"><g id="SvgjsG1838" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1841" x1="0" y1="0" x2="100" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1842" x1="0" y1="3.85" x2="100" y2="3.85" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1843" x1="0" y1="7.7" x2="100" y2="7.7" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1844" x1="0" y1="11.55" x2="100" y2="11.55" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1845" x1="0" y1="15.4" x2="100" y2="15.4" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1846" x1="0" y1="19.25" x2="100" y2="19.25" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1847" x1="0" y1="23.1" x2="100" y2="23.1" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1848" x1="0" y1="26.950000000000003" x2="100" y2="26.950000000000003" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1849" x1="0" y1="30.800000000000004" x2="100" y2="30.800000000000004" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1850" x1="0" y1="34.650000000000006" x2="100" y2="34.650000000000006" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1851" x1="0" y1="38.50000000000001" x2="100" y2="38.50000000000001" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1839" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1853" x1="0" y1="38.5" x2="100" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1852" x1="0" y1="1" x2="0" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1840" class="apexcharts-grid-borders" style="display: none;"></g><g id="SvgjsG1828" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG1829" class="apexcharts-series" zIndex="0" seriesName="Value" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1836" d="M 0 5.0217391304347885C1.2660197205378976, 6.038958453719151, 8.351929861011264, 14.787477042867716, 12.5, 15.065217391304351Q21.11220782617121, 5.6544000090962925, 25, 6.695652173913047Q33.612207826171215, 22.80212173003415, 37.5, 21.760869565217394Q47.12309874351745, 1.9262730152100584, 50, 7.105427357601002e-15Q58.351929861011264, 4.743998781998152, 62.5, 5.0217391304347885Q72.29869486008117, -0.3156569160967291, 75, 1.6739130434782652Q84.62309874351746, 21.508509593485606, 87.5, 23.434782608695656Q98.2061791749352, 19.13369149667821, 100, 18.41304347826087" fill="none" fill-opacity="1" stroke="url(#SvgjsLinearGradient1832)" stroke-opacity="1" stroke-linecap="butt" stroke-width="1.5" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMask98hf3ov6)" pathTo="M 0 5.0217391304347885C1.2660197205378976, 6.038958453719151, 8.351929861011264, 14.787477042867716, 12.5, 15.065217391304351Q21.11220782617121, 5.6544000090962925, 25, 6.695652173913047Q33.612207826171215, 22.80212173003415, 37.5, 21.760869565217394Q47.12309874351745, 1.9262730152100584, 50, 7.105427357601002e-15Q58.351929861011264, 4.743998781998152, 62.5, 5.0217391304347885Q72.29869486008117, -0.3156569160967291, 75, 1.6739130434782652Q84.62309874351746, 21.508509593485606, 87.5, 23.434782608695656Q98.2061791749352, 19.13369149667821, 100, 18.41304347826087" pathFrom="M 0 5.0217391304347885C1.2660197205378976, 6.038958453719151, 8.351929861011264, 14.787477042867716, 12.5, 15.065217391304351Q21.11220782617121, 5.6544000090962925, 25, 6.695652173913047Q33.612207826171215, 22.80212173003415, 37.5, 21.760869565217394Q47.12309874351745, 1.9262730152100584, 50, 7.105427357601002e-15Q58.351929861011264, 4.743998781998152, 62.5, 5.0217391304347885Q72.29869486008117, -0.3156569160967291, 75, 1.6739130434782652Q84.62309874351746, 21.508509593485606, 87.5, 23.434782608695656Q98.2061791749352, 19.13369149667821, 100, 18.41304347826087" fill-rule="evenodd"></path><g id="SvgjsG1830" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"></g></g><g id="SvgjsG1831" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1854" x1="0" y1="0" x2="100" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1855" x1="0" y1="0" x2="100" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1856" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1857" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1868" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1869" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1870" class="apexcharts-point-annotations"></g></g></svg></div></div>
                        </div>
                        <div class="flex items-center justify-between !mt-1">
                          <div>
                            <a class="text-primary text-[0.813rem]" href="javascript:void(0);">View All<i class="ti ti-arrow-narrow-right ms-2 font-semibold inline-block"></i></a>
                          </div>
                          <div class="text-end">
                            <p class="mb-0 text-success text-[0.813rem] font-semibold">+40%</p>
                            <p class="text-[#8c9097] dark:text-white/50 opacity-[0.7] text-[0.6875rem]">this month</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="xxl:col-span-6 xl:col-span-6 col-span-12">
                <div class="box overflow-hidden">
                  <div class="box-body">
                    <div class="flex items-top justify-between">
                      <div>
                        <span class="!text-[0.8rem]  !w-[2.5rem] !h-[2.5rem] !leading-[2.5rem] !rounded-full inline-flex items-center justify-center bg-secondary">
                          <i class="ti ti-wallet text-[1rem] text-white"></i>
                        </span>
                      </div>
                      <div class="flex-grow ms-4">
                        <div class="flex items-center justify-between flex-wrap">
                          <div>
                            <p class="text-[#8c9097] dark:text-white/50 text-[0.813rem] mb-0">Total Revenue</p>
                            <h4 class="font-semibold text-[1.5rem] !mb-2 ">$56,562</h4>
                          </div>
                          <div id="crm-total-revenue" style="min-height: 40px;"><div id="apexcharts7q06zl8c" class="apexcharts-canvas apexcharts7q06zl8c apexcharts-theme-light" style="width: 100px; height: 40px;"><svg id="SvgjsSvg1871" width="100" height="40" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="100" height="40"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 20px;"></div></foreignObject><rect id="SvgjsRect1875" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1918" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1873" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0.75)"><defs id="SvgjsDefs1872"><clipPath id="gridRectMask7q06zl8c"><rect id="SvgjsRect1877" width="105.5" height="48.5" x="-3.5" y="-5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask7q06zl8c"></clipPath><clipPath id="nonForecastMask7q06zl8c"></clipPath><clipPath id="gridRectMarkerMask7q06zl8c"><rect id="SvgjsRect1878" width="104" height="42.5" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient1883" x1="0" y1="1" x2="1" y2="1"><stop id="SvgjsStop1884" stop-opacity="0.9" stop-color="rgba(18,92,115,0.9)" offset="0"></stop><stop id="SvgjsStop1885" stop-opacity="0.9" stop-color="rgba(35,183,229,0.9)" offset="0.98"></stop><stop id="SvgjsStop1886" stop-opacity="0.9" stop-color="rgba(35,183,229,0.9)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine1876" x1="0" y1="0" x2="0" y2="38.5" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="38.5" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1888" class="apexcharts-grid"><g id="SvgjsG1889" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1892" x1="0" y1="0" x2="100" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1893" x1="0" y1="3.85" x2="100" y2="3.85" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1894" x1="0" y1="7.7" x2="100" y2="7.7" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1895" x1="0" y1="11.55" x2="100" y2="11.55" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1896" x1="0" y1="15.4" x2="100" y2="15.4" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1897" x1="0" y1="19.25" x2="100" y2="19.25" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1898" x1="0" y1="23.1" x2="100" y2="23.1" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1899" x1="0" y1="26.950000000000003" x2="100" y2="26.950000000000003" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1900" x1="0" y1="30.800000000000004" x2="100" y2="30.800000000000004" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1901" x1="0" y1="34.650000000000006" x2="100" y2="34.650000000000006" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1902" x1="0" y1="38.50000000000001" x2="100" y2="38.50000000000001" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1890" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1904" x1="0" y1="38.5" x2="100" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1903" x1="0" y1="1" x2="0" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1891" class="apexcharts-grid-borders" style="display: none;"></g><g id="SvgjsG1879" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG1880" class="apexcharts-series" zIndex="0" seriesName="Value" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1887" d="M 0 7.699999999999999C1.347200540556349, 8.695850639579252, 8.333333333333332, 16.94, 12.5, 16.94Q21.495744860008934, 9.22356785117772, 25, 7.699999999999999Q34.43037455109437, 2.7849235327818898, 37.5, 4.619999999999997Q46.979485632983824, 22.779363149918037, 50, 24.64Q59.479485632983824, 21.880636850081963, 62.5, 20.02Q70.89563043957023, 8.73434167015505, 75, 9.239999999999998Q83.83410925870035, 24.454913217984352, 87.5, 23.1Q99.5281349355908, 0.8720066390282085, 100, 0" fill="none" fill-opacity="1" stroke="url(#SvgjsLinearGradient1883)" stroke-opacity="1" stroke-linecap="butt" stroke-width="1.5" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMask7q06zl8c)" pathTo="M 0 7.699999999999999C1.347200540556349, 8.695850639579252, 8.333333333333332, 16.94, 12.5, 16.94Q21.495744860008934, 9.22356785117772, 25, 7.699999999999999Q34.43037455109437, 2.7849235327818898, 37.5, 4.619999999999997Q46.979485632983824, 22.779363149918037, 50, 24.64Q59.479485632983824, 21.880636850081963, 62.5, 20.02Q70.89563043957023, 8.73434167015505, 75, 9.239999999999998Q83.83410925870035, 24.454913217984352, 87.5, 23.1Q99.5281349355908, 0.8720066390282085, 100, 0" pathFrom="M -1 38.5 L -1 38.5 L 12.5 38.5 L 25 38.5 L 37.5 38.5 L 50 38.5 L 62.5 38.5 L 75 38.5 L 87.5 38.5 L 100 38.5" fill-rule="evenodd"></path><g id="SvgjsG1881" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"></g></g><g id="SvgjsG1882" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1905" x1="0" y1="0" x2="100" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1906" x1="0" y1="0" x2="100" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1907" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1908" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1919" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1920" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1921" class="apexcharts-point-annotations"></g></g></svg></div></div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                          <div>
                            <a class="text-secondary text-[0.813rem]" href="javascript:void(0);">View All<i class="ti ti-arrow-narrow-right ms-2 font-semibold inline-block"></i></a>
                          </div>
                          <div class="text-end">
                            <p class="mb-0 text-success text-[0.813rem] font-semibold">+25%</p>
                            <p class="text-[#8c9097] dark:text-white/50 opacity-[0.7] text-[0.6875rem]">this month</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="xxl:col-span-6 xl:col-span-6 col-span-12">
                <div class="box overflow-hidden">
                  <div class="box-body">
                    <div class="flex items-top justify-between">
                      <div>
                        <span class="!text-[0.8rem]  !w-[2.5rem] !h-[2.5rem] !leading-[2.5rem] !rounded-full inline-flex items-center justify-center bg-success">
                          <i class="ti ti-wave-square text-[1rem] text-white"></i>
                        </span>
                      </div>
                      <div class="flex-grow ms-4">
                        <div class="flex items-center justify-between flex-wrap">
                          <div>
                            <p class="text-[#8c9097] dark:text-white/50 text-[0.813rem] mb-0">Conversion Ratio</p>
                            <h4 class="font-semibold text-[1.5rem] !mb-2 ">12.08%</h4>
                          </div>
                          <div id="crm-conversion-ratio" style="min-height: 40px;"><div id="apexchartswnayrqbl" class="apexcharts-canvas apexchartswnayrqbl apexcharts-theme-light" style="width: 100px; height: 40px;"><svg id="SvgjsSvg1922" width="100" height="40" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="100" height="40"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 20px;"></div></foreignObject><rect id="SvgjsRect1926" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1969" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1924" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0.75)"><defs id="SvgjsDefs1923"><clipPath id="gridRectMaskwnayrqbl"><rect id="SvgjsRect1928" width="105.5" height="48.5" x="-3.5" y="-5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskwnayrqbl"></clipPath><clipPath id="nonForecastMaskwnayrqbl"></clipPath><clipPath id="gridRectMarkerMaskwnayrqbl"><rect id="SvgjsRect1929" width="104" height="42.5" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient1934" x1="0" y1="1" x2="1" y2="1"><stop id="SvgjsStop1935" stop-opacity="0.9" stop-color="rgba(19,96,74,0.9)" offset="0"></stop><stop id="SvgjsStop1936" stop-opacity="0.9" stop-color="rgba(38,191,148,0.9)" offset="0.98"></stop><stop id="SvgjsStop1937" stop-opacity="0.9" stop-color="rgba(38,191,148,0.9)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine1927" x1="0" y1="0" x2="0" y2="38.5" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="38.5" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1939" class="apexcharts-grid"><g id="SvgjsG1940" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1943" x1="0" y1="0" x2="100" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1944" x1="0" y1="3.85" x2="100" y2="3.85" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1945" x1="0" y1="7.7" x2="100" y2="7.7" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1946" x1="0" y1="11.55" x2="100" y2="11.55" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1947" x1="0" y1="15.4" x2="100" y2="15.4" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1948" x1="0" y1="19.25" x2="100" y2="19.25" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1949" x1="0" y1="23.1" x2="100" y2="23.1" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1950" x1="0" y1="26.950000000000003" x2="100" y2="26.950000000000003" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1951" x1="0" y1="30.800000000000004" x2="100" y2="30.800000000000004" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1952" x1="0" y1="34.650000000000006" x2="100" y2="34.650000000000006" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1953" x1="0" y1="38.50000000000001" x2="100" y2="38.50000000000001" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1941" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1955" x1="0" y1="38.5" x2="100" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1954" x1="0" y1="1" x2="0" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1942" class="apexcharts-grid-borders" style="display: none;"></g><g id="SvgjsG1930" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG1931" class="apexcharts-series" zIndex="0" seriesName="Value" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1938" d="M 0 7.699999999999999C2.0833333333333335, 7.699999999999999, 8.333333333333332, 7.699999999999999, 12.5, 7.699999999999999Q22.144440121659827, 2.685072626436696, 25, 4.619999999999997Q34.1474952745239, 22.987885671285376, 37.5, 24.64Q46.979485632983824, 18.800636850081965, 50, 16.94Q58.571824495604666, 8.272097555716988, 62.5, 9.239999999999998Q71.33410925870035, 24.454913217984352, 75, 23.1Q83.39563043957023, 0.505658329844948, 87.5, 0Q99.41563486296153, 19.08408079651919, 100, 20.02" fill="none" fill-opacity="1" stroke="url(#SvgjsLinearGradient1934)" stroke-opacity="1" stroke-linecap="butt" stroke-width="1.5" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMaskwnayrqbl)" pathTo="M 0 7.699999999999999C2.0833333333333335, 7.699999999999999, 8.333333333333332, 7.699999999999999, 12.5, 7.699999999999999Q22.144440121659827, 2.685072626436696, 25, 4.619999999999997Q34.1474952745239, 22.987885671285376, 37.5, 24.64Q46.979485632983824, 18.800636850081965, 50, 16.94Q58.571824495604666, 8.272097555716988, 62.5, 9.239999999999998Q71.33410925870035, 24.454913217984352, 75, 23.1Q83.39563043957023, 0.505658329844948, 87.5, 0Q99.41563486296153, 19.08408079651919, 100, 20.02" pathFrom="M -1 38.5 L -1 38.5 L 12.5 38.5 L 25 38.5 L 37.5 38.5 L 50 38.5 L 62.5 38.5 L 75 38.5 L 87.5 38.5 L 100 38.5" fill-rule="evenodd"></path><g id="SvgjsG1932" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"></g></g><g id="SvgjsG1933" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1956" x1="0" y1="0" x2="100" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1957" x1="0" y1="0" x2="100" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1958" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1959" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1970" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1971" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1972" class="apexcharts-point-annotations"></g></g></svg></div></div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                          <div>
                            <a class="text-success text-[0.813rem]" href="javascript:void(0);">View All<i class="ti ti-arrow-narrow-right ms-2 font-semibold inline-block"></i></a>
                          </div>
                          <div class="text-end">
                            <p class="mb-0 text-danger text-[0.813rem] font-semibold">-12%</p>
                            <p class="text-[#8c9097] dark:text-white/50 opacity-[0.7] text-[0.6875rem]">this month</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="xxl:col-span-6 xl:col-span-6 col-span-12">
                <div class="box overflow-hidden">
                  <div class="box-body">
                    <div class="flex items-top justify-between">
                      <div>
                        <span class="!text-[0.8rem]  !w-[2.5rem] !h-[2.5rem] !leading-[2.5rem] !rounded-full inline-flex items-center justify-center bg-warning">
                          <i class="ti ti-briefcase text-[1rem] text-white"></i>
                        </span>
                      </div>
                      <div class="flex-grow ms-4">
                        <div class="flex items-center justify-between flex-wrap">
                          <div>
                            <p class="text-[#8c9097] dark:text-white/50 text-[0.813rem] mb-0">Total Deals</p>
                            <h4 class="font-semibold text-[1.5rem] !mb-2 ">2,543</h4>
                          </div>
                          <div id="crm-total-deals" style="min-height: 40px;"><div id="apexcharts12zybmay" class="apexcharts-canvas apexcharts12zybmay apexcharts-theme-light" style="width: 100px; height: 40px;"><svg id="SvgjsSvg1973" width="100" height="40" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="100" height="40"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml" style="max-height: 20px;"></div></foreignObject><rect id="SvgjsRect1977" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2020" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1975" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0.75)"><defs id="SvgjsDefs1974"><clipPath id="gridRectMask12zybmay"><rect id="SvgjsRect1979" width="105.5" height="48.5" x="-3.5" y="-5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask12zybmay"></clipPath><clipPath id="nonForecastMask12zybmay"></clipPath><clipPath id="gridRectMarkerMask12zybmay"><rect id="SvgjsRect1980" width="104" height="42.5" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient1985" x1="0" y1="1" x2="1" y2="1"><stop id="SvgjsStop1986" stop-opacity="0.9" stop-color="rgba(123,92,37,0.9)" offset="0"></stop><stop id="SvgjsStop1987" stop-opacity="0.9" stop-color="rgba(245,184,73,0.9)" offset="0.98"></stop><stop id="SvgjsStop1988" stop-opacity="0.9" stop-color="rgba(245,184,73,0.9)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine1978" x1="0" y1="0" x2="0" y2="38.5" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="38.5" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1990" class="apexcharts-grid"><g id="SvgjsG1991" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1994" x1="0" y1="0" x2="100" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1995" x1="0" y1="3.85" x2="100" y2="3.85" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1996" x1="0" y1="7.7" x2="100" y2="7.7" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1997" x1="0" y1="11.55" x2="100" y2="11.55" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1998" x1="0" y1="15.4" x2="100" y2="15.4" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1999" x1="0" y1="19.25" x2="100" y2="19.25" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2000" x1="0" y1="23.1" x2="100" y2="23.1" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2001" x1="0" y1="26.950000000000003" x2="100" y2="26.950000000000003" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2002" x1="0" y1="30.800000000000004" x2="100" y2="30.800000000000004" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2003" x1="0" y1="34.650000000000006" x2="100" y2="34.650000000000006" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2004" x1="0" y1="38.50000000000001" x2="100" y2="38.50000000000001" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1992" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine2006" x1="0" y1="38.5" x2="100" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2005" x1="0" y1="1" x2="0" y2="38.5" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1993" class="apexcharts-grid-borders" style="display: none;"></g><g id="SvgjsG1981" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG1982" class="apexcharts-series" zIndex="0" seriesName="Value" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1989" d="M 0 7.699999999999999C2.0833333333333335, 7.699999999999999, 8.333333333333332, 7.699999999999999, 12.5, 7.699999999999999Q22.144440121659827, 2.685072626436696, 25, 4.619999999999997Q34.479485632983824, 22.779363149918037, 37.5, 24.64Q46.19435229702806, 21.192139492515356, 50, 20.02Q58.986593154277976, 18.454981031875338, 62.5, 16.94Q71.07182449560467, 8.272097555716988, 75, 9.239999999999998Q83.83410925870035, 24.454913217984352, 87.5, 23.1Q99.5281349355908, 0.8720066390282085, 100, 0" fill="none" fill-opacity="1" stroke="url(#SvgjsLinearGradient1985)" stroke-opacity="1" stroke-linecap="butt" stroke-width="1.5" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMask12zybmay)" pathTo="M 0 7.699999999999999C2.0833333333333335, 7.699999999999999, 8.333333333333332, 7.699999999999999, 12.5, 7.699999999999999Q22.144440121659827, 2.685072626436696, 25, 4.619999999999997Q34.479485632983824, 22.779363149918037, 37.5, 24.64Q46.19435229702806, 21.192139492515356, 50, 20.02Q58.986593154277976, 18.454981031875338, 62.5, 16.94Q71.07182449560467, 8.272097555716988, 75, 9.239999999999998Q83.83410925870035, 24.454913217984352, 87.5, 23.1Q99.5281349355908, 0.8720066390282085, 100, 0" pathFrom="M -1 38.5 L -1 38.5 L 12.5 38.5 L 25 38.5 L 37.5 38.5 L 50 38.5 L 62.5 38.5 L 75 38.5 L 87.5 38.5 L 100 38.5" fill-rule="evenodd"></path><g id="SvgjsG1983" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"></g></g><g id="SvgjsG1984" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine2007" x1="0" y1="0" x2="100" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2008" x1="0" y1="0" x2="100" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2009" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2010" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG2021" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2022" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2023" class="apexcharts-point-annotations"></g></g></svg></div></div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                          <div>
                            <a class="text-warning text-[0.813rem]" href="javascript:void(0);">View All<i class="ti ti-arrow-narrow-right ms-2 font-semibold inline-block"></i></a>
                          </div>
                          <div class="text-end">
                            <p class="mb-0 text-success text-[0.813rem] font-semibold">+19%</p>
                            <p class="text-[#8c9097] dark:text-white/50  opacity-[0.7] text-[0.6875rem]">this month</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="xxl:col-span-12 xl:col-span-12 col-span-12">
                <div class="box">
                  <div class="box-header !gap-0 !m-0 justify-between">
                    <div class="box-title">
                      Revenue Analytics
                    </div>
                    <div class="hs-dropdown ti-dropdown">
                      <a href="javascript:void(0);" class="text-[0.75rem] px-2 font-normal text-[#8c9097] dark:text-white/50" aria-expanded="false">
                        View All<i class="ri-arrow-down-s-line align-middle ms-1 inline-block"></i>
                      </a>
                      <ul class="hs-dropdown-menu ti-dropdown-menu hidden" role="menu">
                        <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Today</a></li>
                        <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">This Week</a></li>
                        <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Last Week</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="box-body !py-5">
                    <div id="crm-revenue-analytics" style="min-height: 365px;"><div id="apexcharts9o4g0vf9" class="apexcharts-canvas apexcharts9o4g0vf9 apexcharts-theme-light" style="width: 764px; height: 350px;"><svg id="SvgjsSvg2027" width="764" height="350" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><foreignObject x="0" y="0" width="764" height="350"><div class="apexcharts-legend apexcharts-align-center apx-legend-position-bottom" xmlns="http://www.w3.org/1999/xhtml" style="inset: auto 0px 1px; position: absolute; max-height: 175px;"><div class="apexcharts-legend-series" rel="3" seriesname="Sales" data:collapsed="false" style="margin: 2px 5px;"><span class="apexcharts-legend-marker" rel="3" data:collapsed="false" style="background: rgba(119, 119, 142, 0.05) !important; color: rgba(119, 119, 142, 0.05); height: 12px; width: 12px; left: 0px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span class="apexcharts-legend-text" rel="3" i="2" data:default-text="Sales" data:collapsed="false" style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Sales</span></div><div class="apexcharts-legend-series" rel="2" seriesname="Revenue" data:collapsed="false" style="margin: 2px 5px;"><span class="apexcharts-legend-marker" rel="2" data:collapsed="false" style="background: rgba(35, 183, 229, 0.85) !important; color: rgba(35, 183, 229, 0.85); height: 12px; width: 12px; left: 0px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span class="apexcharts-legend-text" rel="2" i="1" data:default-text="Revenue" data:collapsed="false" style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Revenue</span></div><div class="apexcharts-legend-series" rel="1" seriesname="Profit" data:collapsed="false" style="margin: 2px 5px;"><span class="apexcharts-legend-marker" rel="1" data:collapsed="false" style="background: rgb(132, 90, 223) !important; color: rgb(132, 90, 223); height: 12px; width: 12px; left: 0px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span class="apexcharts-legend-text" rel="1" i="0" data:default-text="Profit" data:collapsed="false" style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Profit</span></div></div><style type="text/css">	
  
.apexcharts-legend {	
  display: flex;	
  overflow: auto;	
  padding: 0 10px;	
}	
.apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {	
  flex-wrap: wrap	
}	
.apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {	
  flex-direction: column;	
  bottom: 0;	
}	
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {	
  justify-content: flex-start;	
}	
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {	
  justify-content: center;  	
}	
.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {	
  justify-content: flex-end;	
}	
.apexcharts-legend-series {	
  cursor: pointer;	
  line-height: normal;	
}	
.apexcharts-legend.apx-legend-position-bottom .apexcharts-legend-series, .apexcharts-legend.apx-legend-position-top .apexcharts-legend-series{	
  display: flex;	
  align-items: center;	
}	
.apexcharts-legend-text {	
  position: relative;	
  font-size: 14px;	
}	
.apexcharts-legend-text *, .apexcharts-legend-marker * {	
  pointer-events: none;	
}	
.apexcharts-legend-marker {	
  position: relative;	
  display: inline-block;	
  cursor: pointer;	
  margin-right: 3px;	
  border-style: solid;
}	
    
.apexcharts-legend.apexcharts-align-right .apexcharts-legend-series, .apexcharts-legend.apexcharts-align-left .apexcharts-legend-series{	
  display: inline-block;	
}	
.apexcharts-legend-series.apexcharts-no-click {	
  cursor: auto;	
}	
.apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {	
  display: none !important;	
}	
.apexcharts-inactive-legend {	
  opacity: 0.45;	
}</style></foreignObject><text id="SvgjsText2030" font-family="Helvetica, Arial, sans-serif" x="10" y="0" text-anchor="start" dominant-baseline="auto" font-size=".8125rem" font-weight="semibold" fill="#8c9097" class="apexcharts-title-text" style="font-family: Helvetica, Arial, sans-serif; opacity: 1;">Revenue Analytics with sales &amp; profit (USD)</text><rect id="SvgjsRect2033" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG2141" class="apexcharts-yaxis" rel="0" transform="translate(27.59375, 0)"><g id="SvgjsG2142" class="apexcharts-yaxis-texts-g"><text id="SvgjsText2144" font-family="Helvetica, Arial, sans-serif" x="20" y="51.5" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2145">$1000</tspan><title>$1000</title></text><text id="SvgjsText2147" font-family="Helvetica, Arial, sans-serif" x="20" y="100.3696" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2148">$800</tspan><title>$800</title></text><text id="SvgjsText2150" font-family="Helvetica, Arial, sans-serif" x="20" y="149.2392" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2151">$600</tspan><title>$600</title></text><text id="SvgjsText2153" font-family="Helvetica, Arial, sans-serif" x="20" y="198.10880000000003" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2154">$400</tspan><title>$400</title></text><text id="SvgjsText2156" font-family="Helvetica, Arial, sans-serif" x="20" y="246.97840000000002" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2157">$200</tspan><title>$200</title></text><text id="SvgjsText2159" font-family="Helvetica, Arial, sans-serif" x="20" y="295.848" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2160">$0</tspan><title>$0</title></text></g></g><g id="SvgjsG2029" class="apexcharts-inner apexcharts-graphical" transform="translate(57.59375, 50)"><defs id="SvgjsDefs2028"><clipPath id="gridRectMask9o4g0vf9"><rect id="SvgjsRect2035" width="690.236328125" height="256.348" x="-4" y="-6" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask9o4g0vf9"></clipPath><clipPath id="nonForecastMask9o4g0vf9"></clipPath><clipPath id="gridRectMarkerMask9o4g0vf9"><rect id="SvgjsRect2036" width="688.236328125" height="248.348" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><filter id="SvgjsFilter2042" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood2043" flood-color="#000000" flood-opacity="0.1" result="SvgjsFeFlood2043Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite2044" in="SvgjsFeFlood2043Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite2044Out"></feComposite><feOffset id="SvgjsFeOffset2045" dx="0" dy="8" result="SvgjsFeOffset2045Out" in="SvgjsFeComposite2044Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur2046" stdDeviation="3 " result="SvgjsFeGaussianBlur2046Out" in="SvgjsFeOffset2045Out"></feGaussianBlur><feMerge id="SvgjsFeMerge2047" result="SvgjsFeMerge2047Out" in="SourceGraphic"><feMergeNode id="SvgjsFeMergeNode2048" in="SvgjsFeGaussianBlur2046Out"></feMergeNode><feMergeNode id="SvgjsFeMergeNode2049" in="[object Arguments]"></feMergeNode></feMerge><feBlend id="SvgjsFeBlend2050" in="SourceGraphic" in2="SvgjsFeMerge2047Out" mode="normal" result="SvgjsFeBlend2050Out"></feBlend></filter><filter id="SvgjsFilter2052" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood2053" flood-color="#000000" flood-opacity="0.1" result="SvgjsFeFlood2053Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite2054" in="SvgjsFeFlood2053Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite2054Out"></feComposite><feOffset id="SvgjsFeOffset2055" dx="0" dy="8" result="SvgjsFeOffset2055Out" in="SvgjsFeComposite2054Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur2056" stdDeviation="3 " result="SvgjsFeGaussianBlur2056Out" in="SvgjsFeOffset2055Out"></feGaussianBlur><feMerge id="SvgjsFeMerge2057" result="SvgjsFeMerge2057Out" in="SourceGraphic"><feMergeNode id="SvgjsFeMergeNode2058" in="SvgjsFeGaussianBlur2056Out"></feMergeNode><feMergeNode id="SvgjsFeMergeNode2059" in="[object Arguments]"></feMergeNode></feMerge><feBlend id="SvgjsFeBlend2060" in="SourceGraphic" in2="SvgjsFeMerge2057Out" mode="normal" result="SvgjsFeBlend2060Out"></feBlend></filter><filter id="SvgjsFilter2066" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood2067" flood-color="#000000" flood-opacity="0.1" result="SvgjsFeFlood2067Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite2068" in="SvgjsFeFlood2067Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite2068Out"></feComposite><feOffset id="SvgjsFeOffset2069" dx="0" dy="8" result="SvgjsFeOffset2069Out" in="SvgjsFeComposite2068Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur2070" stdDeviation="3 " result="SvgjsFeGaussianBlur2070Out" in="SvgjsFeOffset2069Out"></feGaussianBlur><feMerge id="SvgjsFeMerge2071" result="SvgjsFeMerge2071Out" in="SourceGraphic"><feMergeNode id="SvgjsFeMergeNode2072" in="SvgjsFeGaussianBlur2070Out"></feMergeNode><feMergeNode id="SvgjsFeMergeNode2073" in="[object Arguments]"></feMergeNode></feMerge><feBlend id="SvgjsFeBlend2074" in="SourceGraphic" in2="SvgjsFeMerge2071Out" mode="normal" result="SvgjsFeBlend2074Out"></feBlend></filter><filter id="SvgjsFilter2079" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feFlood id="SvgjsFeFlood2080" flood-color="#000000" flood-opacity="0.1" result="SvgjsFeFlood2080Out" in="SourceGraphic"></feFlood><feComposite id="SvgjsFeComposite2081" in="SvgjsFeFlood2080Out" in2="SourceAlpha" operator="in" result="SvgjsFeComposite2081Out"></feComposite><feOffset id="SvgjsFeOffset2082" dx="0" dy="8" result="SvgjsFeOffset2082Out" in="SvgjsFeComposite2081Out"></feOffset><feGaussianBlur id="SvgjsFeGaussianBlur2083" stdDeviation="3 " result="SvgjsFeGaussianBlur2083Out" in="SvgjsFeOffset2082Out"></feGaussianBlur><feMerge id="SvgjsFeMerge2084" result="SvgjsFeMerge2084Out" in="SourceGraphic"><feMergeNode id="SvgjsFeMergeNode2085" in="SvgjsFeGaussianBlur2083Out"></feMergeNode><feMergeNode id="SvgjsFeMergeNode2086" in="[object Arguments]"></feMergeNode></feMerge><feBlend id="SvgjsFeBlend2087" in="SourceGraphic" in2="SvgjsFeMerge2084Out" mode="normal" result="SvgjsFeBlend2087Out"></feBlend></filter></defs><line id="SvgjsLine2034" x1="0" y1="0" x2="0" y2="244.348" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="244.348" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG2088" class="apexcharts-grid"><g id="SvgjsG2089" class="apexcharts-gridlines-horizontal"><line id="SvgjsLine2093" x1="0" y1="48.869600000000005" x2="684.236328125" y2="48.869600000000005" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2094" x1="0" y1="97.73920000000001" x2="684.236328125" y2="97.73920000000001" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2095" x1="0" y1="146.60880000000003" x2="684.236328125" y2="146.60880000000003" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2096" x1="0" y1="195.47840000000002" x2="684.236328125" y2="195.47840000000002" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG2090" class="apexcharts-gridlines-vertical"></g><line id="SvgjsLine2099" x1="0" y1="244.348" x2="684.236328125" y2="244.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine2098" x1="0" y1="1" x2="0" y2="244.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG2091" class="apexcharts-grid-borders"><line id="SvgjsLine2092" x1="0" y1="0" x2="684.236328125" y2="0" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2097" x1="0" y1="244.348" x2="684.236328125" y2="244.348" stroke="#f1f1f1" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine2140" x1="0" y1="245.348" x2="684.236328125" y2="245.348" stroke="#e0e0e0" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"></line></g><g id="SvgjsG2037" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG2038" class="apexcharts-series" zIndex="2" seriesName="Sales" data:longestSeries="true" rel="1" data:realIndex="2"><path id="SvgjsPath2041" d="M 0 244.348 L 0 195.47840000000002C3.8677543768632265, 190.46458654447184, 42.09713643588097, 111.28939876607832, 62.20330255681818, 114.84356S104.27484798519565, 213.98648183103475, 124.40660511363636, 217.46972S166.39331213798408, 215.81833330337642, 186.60990767045453, 212.58276S230.61873726985166, 133.85899323609922, 248.81321022727272, 127.06096S291.86414051896304, 122.7916052732511, 311.0165127840909, 117.28703999999999S353.0476210938952, 50.38882882683439, 373.21981534090907, 53.75655999999998S416.77424772747503, 131.82016166581036, 435.42311789772725, 138.05662S478.9529981556248, 134.48642028086147, 497.62642045454544, 128.28269999999998S539.1305034663117, 63.165410472452116, 559.8297230113636, 64.01917599999999S603.0143889233087, 127.70155406291065, 622.0330255681818, 133.414008S673.9069663957687, 127.68627731442872, 684.236328125, 127.06096 L 684.236328125 244.348 L 0 244.348M 0 195.47840000000002z" fill="rgba(119, 119, 142, 0.05)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="2" clip-path="url(#gridRectMask9o4g0vf9)" filter="url(#SvgjsFilter2042)" pathTo="M 0 244.348 L 0 195.47840000000002C3.8677543768632265, 190.46458654447184, 42.09713643588097, 111.28939876607832, 62.20330255681818, 114.84356S104.27484798519565, 213.98648183103475, 124.40660511363636, 217.46972S166.39331213798408, 215.81833330337642, 186.60990767045453, 212.58276S230.61873726985166, 133.85899323609922, 248.81321022727272, 127.06096S291.86414051896304, 122.7916052732511, 311.0165127840909, 117.28703999999999S353.0476210938952, 50.38882882683439, 373.21981534090907, 53.75655999999998S416.77424772747503, 131.82016166581036, 435.42311789772725, 138.05662S478.9529981556248, 134.48642028086147, 497.62642045454544, 128.28269999999998S539.1305034663117, 63.165410472452116, 559.8297230113636, 64.01917599999999S603.0143889233087, 127.70155406291065, 622.0330255681818, 133.414008S673.9069663957687, 127.68627731442872, 684.236328125, 127.06096 L 684.236328125 244.348 L 0 244.348M 0 195.47840000000002z" pathFrom="M 0 244.348 L 0 195.47840000000002C3.8677543768632265, 190.46458654447184, 42.09713643588097, 111.28939876607832, 62.20330255681818, 114.84356S104.27484798519565, 213.98648183103475, 124.40660511363636, 217.46972S166.39331213798408, 215.81833330337642, 186.60990767045453, 212.58276S230.61873726985166, 133.85899323609922, 248.81321022727272, 127.06096S291.86414051896304, 122.7916052732511, 311.0165127840909, 117.28703999999999S353.0476210938952, 50.38882882683439, 373.21981534090907, 53.75655999999998S416.77424772747503, 131.82016166581036, 435.42311789772725, 138.05662S478.9529981556248, 134.48642028086147, 497.62642045454544, 128.28269999999998S539.1305034663117, 63.165410472452116, 559.8297230113636, 64.01917599999999S603.0143889233087, 127.70155406291065, 622.0330255681818, 133.414008S673.9069663957687, 127.68627731442872, 684.236328125, 127.06096 L 684.236328125 244.348 L 0 244.348M 0 195.47840000000002z"></path><path id="SvgjsPath2051" d="M 0 195.47840000000002C3.8677543768632265, 190.46458654447184, 42.09713643588097, 111.28939876607832, 62.20330255681818, 114.84356S104.27484798519565, 213.98648183103475, 124.40660511363636, 217.46972S166.39331213798408, 215.81833330337642, 186.60990767045453, 212.58276S230.61873726985166, 133.85899323609922, 248.81321022727272, 127.06096S291.86414051896304, 122.7916052732511, 311.0165127840909, 117.28703999999999S353.0476210938952, 50.38882882683439, 373.21981534090907, 53.75655999999998S416.77424772747503, 131.82016166581036, 435.42311789772725, 138.05662S478.9529981556248, 134.48642028086147, 497.62642045454544, 128.28269999999998S539.1305034663117, 63.165410472452116, 559.8297230113636, 64.01917599999999S603.0143889233087, 127.70155406291065, 622.0330255681818, 133.414008S673.9069663957687, 127.68627731442872, 684.236328125, 127.06096" fill="none" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="2" clip-path="url(#gridRectMask9o4g0vf9)" filter="url(#SvgjsFilter2052)" pathTo="M 0 195.47840000000002C3.8677543768632265, 190.46458654447184, 42.09713643588097, 111.28939876607832, 62.20330255681818, 114.84356S104.27484798519565, 213.98648183103475, 124.40660511363636, 217.46972S166.39331213798408, 215.81833330337642, 186.60990767045453, 212.58276S230.61873726985166, 133.85899323609922, 248.81321022727272, 127.06096S291.86414051896304, 122.7916052732511, 311.0165127840909, 117.28703999999999S353.0476210938952, 50.38882882683439, 373.21981534090907, 53.75655999999998S416.77424772747503, 131.82016166581036, 435.42311789772725, 138.05662S478.9529981556248, 134.48642028086147, 497.62642045454544, 128.28269999999998S539.1305034663117, 63.165410472452116, 559.8297230113636, 64.01917599999999S603.0143889233087, 127.70155406291065, 622.0330255681818, 133.414008S673.9069663957687, 127.68627731442872, 684.236328125, 127.06096" pathFrom="M 0 195.47840000000002C3.8677543768632265, 190.46458654447184, 42.09713643588097, 111.28939876607832, 62.20330255681818, 114.84356S104.27484798519565, 213.98648183103475, 124.40660511363636, 217.46972S166.39331213798408, 215.81833330337642, 186.60990767045453, 212.58276S230.61873726985166, 133.85899323609922, 248.81321022727272, 127.06096S291.86414051896304, 122.7916052732511, 311.0165127840909, 117.28703999999999S353.0476210938952, 50.38882882683439, 373.21981534090907, 53.75655999999998S416.77424772747503, 131.82016166581036, 435.42311789772725, 138.05662S478.9529981556248, 134.48642028086147, 497.62642045454544, 128.28269999999998S539.1305034663117, 63.165410472452116, 559.8297230113636, 64.01917599999999S603.0143889233087, 127.70155406291065, 622.0330255681818, 133.414008S673.9069663957687, 127.68627731442872, 684.236328125, 127.06096" fill-rule="evenodd"></path><g id="SvgjsG2039" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="2"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2164" r="0" cx="0" cy="0" class="apexcharts-marker w18qe9fxk" stroke="#ffffff" fill="rgba(119, 119, 142, 0.05)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g></g><g id="SvgjsG2061" class="apexcharts-line-series apexcharts-plot-series"><g id="SvgjsG2062" class="apexcharts-series" zIndex="0" seriesName="Profit" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath2065" d="M 0 219.91320000000002C8.736073038511305, 216.1383101215233, 41.71740368818075, 195.2913484541514, 62.20330255681818, 193.03492S105.7749089096327, 206.62455925148862, 124.40660511363636, 200.36536S166.07353244696858, 135.43079087439995, 186.60990767045453, 133.414008S229.42199069023286, 183.04437595192994, 248.81321022727272, 188.14796S298.82038648041146, 176.36126058812962, 311.0165127840909, 166.15664S362.8701562618836, 94.42291422458594, 373.21981534090907, 84.055712S420.1908910455868, 32.38433229594902, 435.42311789772725, 41.53915999999998S476.8919862689394, 158.8262, 497.62642045454544, 158.8262S539.0952888257576, 158.8262, 559.8297230113636, 158.8262S601.5826026009802, 195.44493027877516, 622.0330255681818, 193.03492S677.8258708694779, 149.20165197945252, 684.236328125, 144.16532" fill="none" fill-opacity="1" stroke="rgba(132,
90 ,223, 1)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-line" index="0" clip-path="url(#gridRectMask9o4g0vf9)" filter="url(#SvgjsFilter2066)" pathTo="M 0 219.91320000000002C8.736073038511305, 216.1383101215233, 41.71740368818075, 195.2913484541514, 62.20330255681818, 193.03492S105.7749089096327, 206.62455925148862, 124.40660511363636, 200.36536S166.07353244696858, 135.43079087439995, 186.60990767045453, 133.414008S229.42199069023286, 183.04437595192994, 248.81321022727272, 188.14796S298.82038648041146, 176.36126058812962, 311.0165127840909, 166.15664S362.8701562618836, 94.42291422458594, 373.21981534090907, 84.055712S420.1908910455868, 32.38433229594902, 435.42311789772725, 41.53915999999998S476.8919862689394, 158.8262, 497.62642045454544, 158.8262S539.0952888257576, 158.8262, 559.8297230113636, 158.8262S601.5826026009802, 195.44493027877516, 622.0330255681818, 193.03492S677.8258708694779, 149.20165197945252, 684.236328125, 144.16532" pathFrom="M 0 219.91320000000002C8.736073038511305, 216.1383101215233, 41.71740368818075, 195.2913484541514, 62.20330255681818, 193.03492S105.7749089096327, 206.62455925148862, 124.40660511363636, 200.36536S166.07353244696858, 135.43079087439995, 186.60990767045453, 133.414008S229.42199069023286, 183.04437595192994, 248.81321022727272, 188.14796S298.82038648041146, 176.36126058812962, 311.0165127840909, 166.15664S362.8701562618836, 94.42291422458594, 373.21981534090907, 84.055712S420.1908910455868, 32.38433229594902, 435.42311789772725, 41.53915999999998S476.8919862689394, 158.8262, 497.62642045454544, 158.8262S539.0952888257576, 158.8262, 559.8297230113636, 158.8262S601.5826026009802, 195.44493027877516, 622.0330255681818, 193.03492S677.8258708694779, 149.20165197945252, 684.236328125, 144.16532" fill-rule="evenodd"></path><g id="SvgjsG2063" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2165" r="0" cx="0" cy="0" class="apexcharts-marker wo0utyk4kh" stroke="#ffffff" fill="rgba(132,
90 ,223, 1)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2075" class="apexcharts-series" zIndex="1" seriesName="Revenue" data:longestSeries="true" rel="2" data:realIndex="1"><path id="SvgjsPath2078" d="M 0 200.36536C2.5999822924974683, 195.87151165560024, 46.70669172980814, 101.86159681215841, 62.20330255681818, 92.85224S111.58569060259215, 117.96568804109499, 124.40660511363636, 128.038352S166.02918158158656, 192.37004176447903, 186.60990767045453, 190.59144S239.43018342794483, 127.60743532748317, 248.81321022727272, 117.28703999999999S290.844318537077, 50.3888288268344, 311.0165127840909, 53.75655999999998S356.9051584938823, 129.56502706232416, 373.21981534090907, 138.05662S420.11161200917275, 127.62103540431751, 435.42311789772725, 118.50878S477.1854083312877, 61.57012640195317, 497.62642045454544, 64.01917599999999S542.1837098796192, 126.0317103581545, 559.8297230113636, 133.414008S604.6611732373111, 108.42237677449727, 622.0330255681818, 116.06530000000001S679.8113251085579, 183.0201617188425, 684.236328125, 188.14796" fill="none" fill-opacity="1" stroke="rgba(35, 183, 229, 0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="5" class="apexcharts-line" index="1" clip-path="url(#gridRectMask9o4g0vf9)" filter="url(#SvgjsFilter2079)" pathTo="M 0 200.36536C2.5999822924974683, 195.87151165560024, 46.70669172980814, 101.86159681215841, 62.20330255681818, 92.85224S111.58569060259215, 117.96568804109499, 124.40660511363636, 128.038352S166.02918158158656, 192.37004176447903, 186.60990767045453, 190.59144S239.43018342794483, 127.60743532748317, 248.81321022727272, 117.28703999999999S290.844318537077, 50.3888288268344, 311.0165127840909, 53.75655999999998S356.9051584938823, 129.56502706232416, 373.21981534090907, 138.05662S420.11161200917275, 127.62103540431751, 435.42311789772725, 118.50878S477.1854083312877, 61.57012640195317, 497.62642045454544, 64.01917599999999S542.1837098796192, 126.0317103581545, 559.8297230113636, 133.414008S604.6611732373111, 108.42237677449727, 622.0330255681818, 116.06530000000001S679.8113251085579, 183.0201617188425, 684.236328125, 188.14796" pathFrom="M 0 200.36536C2.5999822924974683, 195.87151165560024, 46.70669172980814, 101.86159681215841, 62.20330255681818, 92.85224S111.58569060259215, 117.96568804109499, 124.40660511363636, 128.038352S166.02918158158656, 192.37004176447903, 186.60990767045453, 190.59144S239.43018342794483, 127.60743532748317, 248.81321022727272, 117.28703999999999S290.844318537077, 50.3888288268344, 311.0165127840909, 53.75655999999998S356.9051584938823, 129.56502706232416, 373.21981534090907, 138.05662S420.11161200917275, 127.62103540431751, 435.42311789772725, 118.50878S477.1854083312877, 61.57012640195317, 497.62642045454544, 64.01917599999999S542.1837098796192, 126.0317103581545, 559.8297230113636, 133.414008S604.6611732373111, 108.42237677449727, 622.0330255681818, 116.06530000000001S679.8113251085579, 183.0201617188425, 684.236328125, 188.14796" fill-rule="evenodd"></path><g id="SvgjsG2076" class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="1"><g class="apexcharts-series-markers"><circle id="SvgjsCircle2166" r="0" cx="0" cy="0" class="apexcharts-marker w56a78pap" stroke="#ffffff" fill="rgba(35, 183, 229, 0.85)" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle></g></g></g><g id="SvgjsG2040" class="apexcharts-datalabels" data:realIndex="2"></g><g id="SvgjsG2064" class="apexcharts-datalabels" data:realIndex="0"></g><g id="SvgjsG2077" class="apexcharts-datalabels" data:realIndex="1"></g></g><line id="SvgjsLine2100" x1="0" y1="0" x2="684.236328125" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine2101" x1="0" y1="0" x2="684.236328125" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG2102" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG2103" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText2105" font-family="Helvetica, Arial, sans-serif" x="0" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2106">Jan</tspan><title>Jan</title></text><text id="SvgjsText2108" font-family="Helvetica, Arial, sans-serif" x="62.20330255681817" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2109">Feb</tspan><title>Feb</title></text><text id="SvgjsText2111" font-family="Helvetica, Arial, sans-serif" x="124.40660511363635" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2112">Mar</tspan><title>Mar</title></text><text id="SvgjsText2114" font-family="Helvetica, Arial, sans-serif" x="186.60990767045453" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2115">Apr</tspan><title>Apr</title></text><text id="SvgjsText2117" font-family="Helvetica, Arial, sans-serif" x="248.81321022727272" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2118">May</tspan><title>May</title></text><text id="SvgjsText2120" font-family="Helvetica, Arial, sans-serif" x="311.01651278409093" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2121">Jun</tspan><title>Jun</title></text><text id="SvgjsText2123" font-family="Helvetica, Arial, sans-serif" x="373.2198153409091" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2124">Jul</tspan><title>Jul</title></text><text id="SvgjsText2126" font-family="Helvetica, Arial, sans-serif" x="435.4231178977273" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2127">Aug</tspan><title>Aug</title></text><text id="SvgjsText2129" font-family="Helvetica, Arial, sans-serif" x="497.62642045454544" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2130">Sep</tspan><title>Sep</title></text><text id="SvgjsText2132" font-family="Helvetica, Arial, sans-serif" x="559.8297230113635" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2133">Oct</tspan><title>Oct</title></text><text id="SvgjsText2135" font-family="Helvetica, Arial, sans-serif" x="622.0330255681816" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2136">Nov</tspan><title>Nov</title></text><text id="SvgjsText2138" font-family="Helvetica, Arial, sans-serif" x="684.2363281249998" y="273.348" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan2139">Dec</tspan><title>Dec</title></text></g></g><g id="SvgjsG2161" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG2162" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG2163" class="apexcharts-point-annotations"></g><rect id="SvgjsRect2167" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect id="SvgjsRect2168" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></g></svg><div class="apexcharts-tooltip apexcharts-theme-light"><div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(132, 90, 223);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 2;"><span class="apexcharts-tooltip-marker" style="background-color: rgba(35, 183, 229, 0.85);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 3;"><span class="apexcharts-tooltip-marker" style="background-color: rgba(119, 119, 142, 0.05);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div><div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light"><div class="apexcharts-xaxistooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div><div class="apexcharts-toolbar" style="top: 0px; right: 3px;"><div class="apexcharts-zoomin-icon" title="Zoom In"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
<path d="M0 0h24v24H0z" fill="none"></path>
<path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7zm-1-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>
</svg>
</div><div class="apexcharts-zoomout-icon" title="Zoom Out"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
<path d="M0 0h24v24H0z" fill="none"></path>
<path d="M7 11v2h10v-2H7zm5-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>
</svg>
</div><div class="apexcharts-zoom-icon apexcharts-selected" title="Selection Zoom"><svg xmlns="http://www.w3.org/2000/svg" fill="#000000" height="24" viewBox="0 0 24 24" width="24">
<path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
<path d="M0 0h24v24H0V0z" fill="none"></path>
<path d="M12 10h-2v2H9v-2H7V9h2V7h1v2h2v1z"></path>
</svg></div><div class="apexcharts-pan-icon" title="Panning"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="24" viewBox="0 0 24 24" width="24">
<defs>
  <path d="M0 0h24v24H0z" id="a"></path>
</defs>
<clipPath id="b">
  <use overflow="visible" xlink:href="#a"></use>
</clipPath>
<path clip-path="url(#b)" d="M23 5.5V20c0 2.2-1.8 4-4 4h-7.3c-1.08 0-2.1-.43-2.85-1.19L1 14.83s1.26-1.23 1.3-1.25c.22-.19.49-.29.79-.29.22 0 .42.06.6.16.04.01 4.31 2.46 4.31 2.46V4c0-.83.67-1.5 1.5-1.5S11 3.17 11 4v7h1V1.5c0-.83.67-1.5 1.5-1.5S15 .67 15 1.5V11h1V2.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5V11h1V5.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5z"></path>
</svg></div><div class="apexcharts-reset-icon" title="Reset Zoom"><svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
<path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"></path>
<path d="M0 0h24v24H0z" fill="none"></path>
</svg></div><div class="apexcharts-menu-icon" title="Menu"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path></svg></div><div class="apexcharts-menu"><div class="apexcharts-menu-item exportSVG" title="Download SVG">Download SVG</div><div class="apexcharts-menu-item exportPNG" title="Download PNG">Download PNG</div><div class="apexcharts-menu-item exportCSV" title="Download CSV">Download CSV</div></div></div></div></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="xxl:col-span-12 xl:col-span-12 col-span-12">
            <div class="box custom-card">
              <div class="box-header justify-between">
                <div class="box-title">
                  Deals Statistics
                </div>
                <div class="flex flex-wrap gap-2">
                  <div>
                    <input class="ti-form-control form-control-sm" type="text" placeholder="Search Here" aria-label=".form-control-sm example">
                  </div>
                  <div class="hs-dropdown ti-dropdown">
                    <a href="javascript:void(0);" class="ti-btn ti-btn-primary !bg-primary !text-white !py-1 !px-2 !text-[0.75rem] !m-0 !gap-0 !font-medium" aria-expanded="false">
                      Sort By<i class="ri-arrow-down-s-line align-middle ms-1 inline-block"></i>
                    </a>
                    <ul class="hs-dropdown-menu ti-dropdown-menu hidden" role="menu">
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">New</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Popular</a></li>
                      <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Relevant</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="overflow-x-auto">
                  <table class="table min-w-full whitespace-nowrap table-hover border table-bordered">
                    <thead>
                      <tr class="border border-inherit border-solid dark:border-defaultborder/10">
                        <th scope="row" class="!ps-4 !pe-5"><input class="form-check-input" type="checkbox" id="checkboxNoLabel1" value="" aria-label="..."></th>
                        <th scope="col" class="!text-start !text-[0.85rem] min-w-[200px]">Sales Rep</th>
                        <th scope="col" class="!text-start !text-[0.85rem]">Category</th>
                        <th scope="col" class="!text-start !text-[0.85rem]">Mail</th>
                        <th scope="col" class="!text-start !text-[0.85rem]">Location</th>
                        <th scope="col" class="!text-start !text-[0.85rem]">Date</th>
                        <th scope="col" class="!text-start !text-[0.85rem]">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="border border-inherit border-solid hover:bg-gray-100 dark:border-defaultborder/10 dark:hover:bg-light">
                        <th scope="row" class="!ps-4 !pe-5"><input class="form-check-input" type="checkbox" id="checkboxNoLabel2" value="" aria-label="..."></th>
                        <td>
                          <div class="flex items-center font-semibold">
                            <span class="!me-2 inline-flex justify-center items-center">
                              <img src="../assets/images/faces/4.jpg" alt="img" class="w-[1.75rem] h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full">
                            </span>Mayor Kelly
                          </div>
                        </td>
                        <td>Manufacture</td>
                        <td>mayorkelly@gmail.com</td>
                        <td>
                          <span class="inline-flex text-info !py-[0.15rem] !px-[0.45rem] rounded-sm !font-semibold !text-[0.75em] bg-info/10">Germany</span>
                        </td>
                        <td>Sep 15 - Oct 12, 2023</td>
                        <td>
                          <div class="flex flex-row items-center !gap-2 text-[0.9375rem]">
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-success/10 text-success hover:bg-success hover:text-white hover:border-success"><i class="ri-download-2-line"></i></a>
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-primary/10 text-primary hover:bg-primary hover:text-white hover:border-primary"><i class="ri-edit-line"></i></a>
                          </div>
                        </td>
                      </tr>
                      <tr class="border border-inherit border-solid hover:bg-gray-100 dark:border-defaultborder/10 dark:hover:bg-light">
                        <th scope="row" class="!ps-4 !pe-5"><input class="form-check-input" type="checkbox" id="checkboxNoLabel13" value="" aria-label="..." checked=""></th>
                        <td>
                          <div class="flex items-center font-semibold">
                            <span class="inline-flex  justify-center items-center me-2">
                              <img src="../assets/images/faces/15.jpg" alt="img" class="w-[1.75rem] h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full">
                            </span>Andrew Garfield
                          </div>
                        </td>
                        <td>Development</td>
                        <td>andrewgarfield@gmail.com</td>
                        <td>
                          <span class="inline-flex text-primary !py-[0.15rem] !px-[0.45rem] rounded-sm !font-semibold !text-[0.75em] bg-primary/10">Canada</span>
                        </td>
                        <td>Apr 10 - Dec 12, 2023</td>
                        <td>
                          <div class="flex flex-row items-center !gap-2 text-[0.9375rem]">
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-success/10 text-success hover:bg-success hover:text-white hover:border-success"><i class="ri-download-2-line"></i></a>
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-primary/10 text-primary hover:bg-primary hover:text-white hover:border-primary"><i class="ri-edit-line"></i></a>
                          </div>
                        </td>
                      </tr>
                      <tr class="border border-inherit border-solid hover:bg-gray-100 dark:border-defaultborder/10 dark:hover:bg-light">
                        <th scope="row" class="!ps-4 !pe-5"><input class="form-check-input" type="checkbox" id="checkboxNoLabel4" value="" aria-label="..."></th>
                        <td>
                          <div class="flex items-center font-semibold">
                            <span class="inline-flex  justify-center items-center me-2">
                              <img src="../assets/images/faces/11.jpg" alt="img" class="w-[1.75rem] h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full">
                            </span>Simon Cowel
                          </div>
                        </td>
                        <td>Service</td>
                        <td>simoncowel234@gmail.com</td>
                        <td>
                          <span class="inline-flex text-danger !py-[0.15rem] !px-[0.45rem] rounded-sm !font-semibold !text-[0.75em] bg-danger/10">Europe</span>
                        </td>
                        <td>Sep 15 - Oct 12, 2023</td>
                        <td>
                          <div class="flex flex-row items-center !gap-2 text-[0.9375rem]">
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-success/10 text-success hover:bg-success hover:text-white hover:border-success"><i class="ri-download-2-line"></i></a>
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-primary/10 text-primary hover:bg-primary hover:text-white hover:border-primary"><i class="ri-edit-line"></i></a>
                          </div>
                        </td>
                      </tr>
                      <tr class="border border-inherit border-solid hover:bg-gray-100 dark:border-defaultborder/10 dark:hover:bg-light">
                        <th scope="row" class="!ps-4 !pe-5"><input class="form-check-input" type="checkbox" id="checkboxNoLabel5" value="" aria-label="..." checked=""></th>
                        <td>
                          <div class="flex items-center font-semibold">
                            <span class="inline-flex justify-center items-center me-2">
                              <img src="../assets/images/faces/8.jpg" alt="img" class="w-[1.75rem] h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full">
                            </span>Mirinda Hers
                          </div>
                        </td>
                        <td>Marketing</td>
                        <td>mirindahers@gmail.com</td>
                        <td>
                          <span class="inline-flex text-warning !py-[0.15rem] !px-[0.45rem] rounded-sm !font-semibold !text-[0.75em] bg-warning/10">USA</span>
                        </td>
                        <td>Apr 14 - Dec 14, 2023</td>
                        <td>
                          <div class="flex flex-row items-center !gap-2  text-[0.9375rem]">
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-success/10 text-success hover:bg-success hover:text-white hover:border-success"><i class="ri-download-2-line"></i></a>
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !gap-0 !m-0 !h-[1.75rem] !w-[1.75rem] text-[0.8rem] bg-primary/10 text-primary hover:bg-primary hover:text-white hover:border-primary"><i class="ri-edit-line"></i></a>
                          </div>
                        </td>
                      </tr>
                      <tr class="border border-inherit border-solid hover:bg-gray-100 dark:border-defaultborder/10 dark:hover:bg-light">
                        <th scope="row" class="!ps-4 !pe-5"><input class="form-check-input" type="checkbox" id="checkboxNoLabel3" value="" aria-label="..." checked=""></th>
                        <td>
                          <div class="flex items-center font-semibold">
                            <span class="inline-flex  justify-center items-center me-2">
                              <img src="../assets/images/faces/9.jpg" alt="img" class="w-[1.75rem] h-[1.75rem] leading-[1.75rem] text-[0.65rem]  rounded-full">
                            </span>Jacob Smith
                          </div>
                        </td>
                        <td>Social Plataform</td>
                        <td>jacobsmith@gmail.com</td>
                        <td>
                          <span class="inline-flex text-success !py-[0.15rem] !px-[0.45rem] rounded-sm !font-semibold !text-[0.75em] bg-success/10">Singapore</span>
                        </td>
                        <td>Feb 25 - Nov 25, 2023</td>
                        <td>
                          <div class="flex flex-row items-center !gap-2 text-[0.9375rem]">
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !m-0 !h-[1.75rem] !gap-0 !w-[1.75rem] text-[0.8rem] bg-success/10 text-success hover:bg-success hover:text-white hover:border-success"><i class="ri-download-2-line"></i></a>
                            <a aria-label="anchor" href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-wave !m-0 !h-[1.75rem] !gap-0 !w-[1.75rem] text-[0.8rem] bg-primary/10 text-primary hover:bg-primary hover:text-white hover:border-primary"><i class="ri-edit-line"></i></a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="box-footer">
                <div class="sm:flex items-center">
                  <div class="text-defaulttextcolor dark:text-defaulttextcolor/70">
                    Showing 5 Entries <i class="bi bi-arrow-right ms-2 font-semibold"></i>
                  </div>
                  <div class="ms-auto">
                    <nav aria-label="Page navigation" class="pagination-style-4">
                      <ul class="ti-pagination mb-0">
                          <li class="page-item disabled">
                              <a class="page-link" href="javascript:void(0);">
                                  Prev
                              </a>
                          </li>
                          <li class="page-item"><a class="page-link active" href="javascript:void(0);">1</a></li>
                          <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                          <li class="page-item">
                              <a class="page-link !text-primary" href="javascript:void(0);">
                                  next
                              </a>
                          </li>
                      </ul>
                  </nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="xxl:col-span-3 xl:col-span-12 col-span-12">
        <div class="grid grid-cols-12 gap-x-6">
          <div class="xxl:col-span-12 xl:col-span-12  col-span-12">
            <div class="box">
              <div class="box-header justify-between">
                <div class="box-title">
                  Leads By Source
                </div>
                <div class="hs-dropdown ti-dropdown">
                  <a aria-label="anchor" href="javascript:void(0);" class="flex items-center justify-center w-[1.75rem] h-[1.75rem] ! !text-[0.8rem] !py-1 !px-2 rounded-sm bg-light border-light shadow-none !font-medium" aria-expanded="false">
                    <i class="fe fe-more-vertical text-[0.8rem]"></i>
                  </a>
                  <ul class="hs-dropdown-menu ti-dropdown-menu hidden">
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Week</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Month</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Year</a></li>
                  </ul>
                </div>
              </div>
              <div class="box-body overflow-hidden">
                <div class="leads-source-chart flex items-center justify-center">
                  <canvas id="leads-source" class="chartjs-chart w-full" width="350" height="300" style="display: block; box-sizing: border-box; height: 300px; width: 350px;"></canvas>
                  <div class="lead-source-value ">
                    <span class="block text-[0.875rem] ">Total</span>
                    <span class="block text-[1.5625rem] font-bold">4,145</span>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-4 border-t border-dashed dark:border-defaultborder/10">
                <div class="col !p-0">
                  <div class="!ps-4 p-[0.95rem] text-center border-e border-dashed dark:border-defaultborder/10">
                    <span class="text-[#8c9097] dark:text-white/50 text-[0.75rem] mb-1 crm-lead-legend mobile inline-block">Mobile
                    </span>
                    <div><span class="text-[1rem]  font-semibold">1,624</span>
                    </div>
                  </div>
                </div>
                <div class="col !p-0">
                  <div class="p-[0.95rem] text-center border-e border-dashed dark:border-defaultborder/10">
                    <span class="text-[#8c9097] dark:text-white/50 text-[0.75rem] mb-1 crm-lead-legend desktop inline-block">Desktop
                    </span>
                    <div><span class="text-[1rem]  font-semibold">1,267</span></div>
                  </div>
                </div>
                <div class="col !p-0">
                  <div class="p-[0.95rem] text-center border-e border-dashed dark:border-defaultborder/10">
                    <span class="text-[#8c9097] dark:text-white/50 text-[0.75rem] mb-1 crm-lead-legend laptop inline-block">Laptop
                    </span>
                    <div><span class="text-[1rem]  font-semibold">1,153</span>
                    </div>
                  </div>
                </div>
                <div class="col !p-0">
                  <div class="!pe-4 p-[0.95rem] text-center">
                    <span class="text-[#8c9097] dark:text-white/50 text-[0.75rem] mb-1 crm-lead-legend tablet inline-block">Tablet
                    </span>
                    <div><span class="text-[1rem]  font-semibold">679</span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="xxl:col-span-12 xl:col-span-6  col-span-12">
            <div class="box">
              <div class="box-header justify-between">
                <div class="box-title">
                  Deals Status
                </div>
                <div class="hs-dropdown ti-dropdown">
                  <a href="javascript:void(0);" class="text-[0.75rem] px-2 font-normal text-[#8c9097] dark:text-white/50" aria-expanded="false">
                    View All<i class="ri-arrow-down-s-line align-middle ms-1 inline-block"></i>
                  </a>
                  <ul class="hs-dropdown-menu ti-dropdown-menu hidden" role="menu">
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Today</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">This Week</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Last Week</a></li>
                  </ul>
                </div>
              </div>
              <div class="box-body">
                <div class="flex items-center mb-[0.8rem]">
                  <h4 class="font-bold mb-0 text-[1.5rem] ">4,289</h4>
                  <div class="ms-2">
                    <span class="py-[0.18rem] px-[0.45rem] rounded-sm text-success !font-medium !text-[0.75em] bg-success/10">1.02<i class="ri-arrow-up-s-fill align-mmiddle ms-1"></i></span>
                    <span class="text-[#8c9097] dark:text-white/50 text-[0.813rem] ms-1">compared to last week</span>
                  </div>
                </div>

                <div class="flex w-full h-[0.3125rem] mb-6 rounded-full overflow-hidden">
                  <div class="flex flex-col justify-center rounded-s-[0.625rem] overflow-hidden bg-primary w-[21%]" aria-valuenow="21" aria-valuemin="0" aria-valuemax="100">
                  </div>
                  <div class="flex flex-col justify-center rounded-none overflow-hidden bg-info w-[26%]" aria-valuenow="26" aria-valuemin="0" aria-valuemax="100">
                  </div>
                  <div class="flex flex-col justify-center rounded-none overflow-hidden bg-warning w-[35%]" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100">
                  </div>
                  <div class="flex flex-col justify-center rounded-e-[0.625rem] overflow-hidden bg-success w-[18%]" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
                  </div>
                </div>
                <ul class="list-none mb-0 pt-2 crm-deals-status">
                  <li class="primary">
                    <div class="flex items-center text-[0.813rem]  justify-between">
                      <div>Successful Deals</div>
                      <div class="text-[0.75rem] text-[#8c9097] dark:text-white/50">987 deals</div>
                    </div>
                  </li>
                  <li class="info">
                    <div class="flex items-center text-[0.813rem]  justify-between">
                      <div>Pending Deals</div>
                      <div class="text-[0.75rem] text-[#8c9097] dark:text-white/50">1,073 deals</div>
                    </div>
                  </li>
                  <li class="warning">
                    <div class="flex items-center text-[0.813rem]  justify-between">
                      <div>Rejected Deals</div>
                      <div class="text-[0.75rem] text-[#8c9097] dark:text-white/50">1,674 deals</div>
                    </div>
                  </li>
                  <li class="success">
                    <div class="flex items-center text-[0.813rem]  justify-between">
                      <div>Upcoming Deals</div>
                      <div class="text-[0.75rem] text-[#8c9097] dark:text-white/50">921 deals</div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="xxl:col-span-12 xl:col-span-6  col-span-12">
            <div class="box">
              <div class="box-header justify-between">
                <div class="box-title">
                  Recent Activity
                </div>
                <div class="hs-dropdown ti-dropdown">
                  <a href="javascript:void(0);" class="text-[0.75rem] px-2 font-normal text-[#8c9097] dark:text-white/50" aria-expanded="false">
                    View All<i class="ri-arrow-down-s-line align-middle ms-1 inline-block"></i>
                  </a>
                  <ul class="hs-dropdown-menu ti-dropdown-menu hidden" role="menu">
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Today</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">This Week</a></li>
                    <li><a class="ti-dropdown-item !py-2 !px-[0.9375rem] !text-[0.8125rem] !font-medium block" href="javascript:void(0);">Last Week</a></li>
                  </ul>
                </div>
              </div>
              <div class="box-body">
                <div>
                  <ul class="list-none mb-0 crm-recent-activity">
                    <li class="crm-recent-activity-content">
                      <div class="flex items-start">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] inline-flex items-center justify-center font-medium leading-[1.25rem] text-[0.65rem] text-primary bg-primary/10 rounded-full">
                            <i class="bi bi-circle-fill text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content text-defaultsize">
                          <span class="font-semibold ">Update of calendar events
                            &amp;</span><span><a href="javascript:void(0);" class="text-primary font-semibold">
                              Added new events in next week.</a></span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">4:45PM</span>
                        </div>
                      </div>
                    </li>
                    <li class="crm-recent-activity-content">
                      <div class="flex items-start  text-defaultsize">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] inline-flex items-center justify-center font-medium text-[0.65rem] text-secondary bg-secondary/10 rounded-full">
                            <i class="bi bi-circle-fill text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content">
                          <span>New theme for <span class="font-semibold">Spruko Website</span> completed</span>
                          <span class="block text-[0.75rem] text-[#8c9097] dark:text-white/50">Lorem ipsum, dolor sit amet.</span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">3 hrs</span>
                        </div>
                      </div>
                    </li>
                    <li class="crm-recent-activity-content  text-defaultsize">
                      <div class="flex items-start">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] inline-flex items-center justify-center font-medium text-[0.65rem] text-success bg-success/10 rounded-full">
                            <i class="bi bi-circle-fill  text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content">
                          <span>Created a <span class="text-success font-semibold">New Task</span> today <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] text-[0.65rem] inline-flex items-center justify-center font-medium bg-purple/10 rounded-full ms-1"><i class="ri-add-fill text-purple text-[0.75rem]"></i></span></span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">22 hrs</span>
                        </div>
                      </div>
                    </li>
                    <li class="crm-recent-activity-content  text-defaultsize">
                      <div class="flex items-start">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] inline-flex items-center justify-center font-medium text-[0.65rem] text-pink bg-pink/10 rounded-full">
                            <i class="bi bi-circle-fill text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content">
                          <span>New member <span class="py-[0.2rem] px-[0.45rem] font-semibold rounded-sm text-pink text-[0.75em] bg-pink/10">@andreas
                              gurrero</span> added today to AI Summit.</span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">Today</span>
                        </div>
                      </div>
                    </li>
                    <li class="crm-recent-activity-content  text-defaultsize">
                      <div class="flex items-start">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] inline-flex items-center justify-center font-medium text-[0.65rem] text-warning bg-warning/10 rounded-full">
                            <i class="bi bi-circle-fill text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content">
                          <span>32 New people joined summit.</span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">22 hrs</span>
                        </div>
                      </div>
                    </li>
                    <li class="crm-recent-activity-content  text-defaultsize">
                      <div class="flex items-start">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] inline-flex items-center justify-center font-medium text-[0.65rem] text-info bg-info/10 rounded-full">
                            <i class="bi bi-circle-fill text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content">
                          <span>Neon Tarly added <span class="text-info font-semibold">Robert Bright</span> to AI
                            summit project.</span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">12 hrs</span>
                        </div>
                      </div>
                    </li>
                    <li class="crm-recent-activity-content  text-defaultsize">
                      <div class="flex items-start">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] inline-flex items-center justify-center font-medium text-[0.65rem] text-[#232323] dark:text-white bg-[#232323]/10 dark:bg-white/20 rounded-full">
                            <i class="bi bi-circle-fill text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content">
                          <span>Replied to new support request <i class="ri-checkbox-circle-line text-success text-[1rem] align-middle"></i></span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">4 hrs</span>
                        </div>
                      </div>
                    </li>
                    <li class="crm-recent-activity-content  text-defaultsize">
                      <div class="flex items-start">
                        <div class="me-4">
                          <span class="w-[1.25rem] h-[1.25rem] leading-[1.25rem] inline-flex items-center justify-center font-medium text-[0.65rem] text-purple bg-purple/10 rounded-full">
                            <i class="bi bi-circle-fill text-[0.5rem]"></i>
                          </span>
                        </div>
                        <div class="crm-timeline-content">
                          <span>Completed documentation of <a href="javascript:void(0);" class="text-purple underline font-semibold">AI Summit.</a></span>
                        </div>
                        <div class="flex-grow text-end">
                          <span class="block text-[#8c9097] dark:text-white/50 text-[0.6875rem] opacity-[0.7]">4 hrs</span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection 

@push('scripts')
    {{-- <script>
        // Data kuartal
        const quarterlyLabels = ['Kuartal 1', 'Kuartal 2', 'Kuartal 3', 'Kuartal 4'];

        // Inisialisasi Bar Chart
        const ctxQuarterly = document.getElementById('quarterlyChart').getContext('2d');
        const quarterlyChart = new Chart(ctxQuarterly, {
            type: 'bar', // Tipe grafik batang
            data: {
                labels: quarterlyLabels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: [
                            {{' $suratMasukPerKuartal'[1] ?? 0 }},
                            {{ $suratMasukPerKuartal[2] ?? 0 }},
                            {{ $suratMasukPerKuartal[3] ?? 0 }},
                            {{ $suratMasukPerKuartal[4] ?? 0 }},
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Surat Didisposisikan',
                        data: [
                            {{ $disposisiPerKuartal[1] ?? 0 }},
                            {{ $disposisiPerKuartal[2] ?? 0 }},
                            {{ $disposisiPerKuartal[3] ?? 0 }},
                            {{ $disposisiPerKuartal[4] ?? 0 }},
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Surat Keluar',
                        data: [
                            {{ $suratKeluarPerKuartal[1] ?? 0 }},
                            {{ $suratKeluarPerKuartal[2] ?? 0 }},
                            {{ $suratKeluarPerKuartal[3] ?? 0 }},
                            {{ $suratKeluarPerKuartal[4] ?? 0 }},
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Data untuk Pie Chart
        const statusLabels = ['Baru', 'Diproses', 'Disposisi', 'Selesai'];
        const statusData = [
            {{ $persentaseBaru }},
            {{ $persentaseDiproses }},
            {{ $persentaseDidisposisi }},
            {{ $persentaseSelesai }}
        ];

        // Inisialisasi Pie Chart
        const ctxStatusPie = document.getElementById('statusPieChart').getContext('2d');
        const statusPieChart = new Chart(ctxStatusPie, {
            type: 'pie', // Tipe grafik lingkaran
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

    </script> --}}
@endpush