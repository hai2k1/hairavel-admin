<template>
  <app-layout>
    <div class="p-5">
      <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-2 gap-5 whitespace-nowrap">
        <div class=" flex shadow bg-white dark:bg-blackgray-4 rounded items-center">
          <div
            class="flex-none rounded-l bg-blue-600 text-white text-xl flex items-center justify-center w-14 h-16">
            PV
          </div>
          <div class="px-4 flex-grow">
            <div class="text-xl">{{$apiNumDay}}</div>
            <div class="text-gray-500 text-xs">Visits today</div>
          </div>
          <div class="flex-none px-4 text-right">
            <div
              class="text-sm flex items-center justify-end {{$apiNumTrend === 1 ? 'text-red-600' : ''}} {{$apiNumTrend === 2 ? 'text-green-600' : ''}} {{$apiNumTrend === 0 ? 'text-blue-600' : ''}} ">
              <div>{{$apiNumRate}}%</div>
              @if($apiNumTrend === 2)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 " fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
              @endif
              @if($apiNumTrend === 1)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
              @endif
              @if($apiNumTrend === 0)
                <div class="ml-1"> -</div>
              @endif
            </div>
            <div class="text-gray-500 text-xs">7 days percentage</div>
          </div>
        </div>
        <div class=" flex shadow bg-white  dark:bg-blackgray-4 rounded items-center">
          <div
            class="flex-none rounded-l bg-red-600 text-white text-xl flex items-center justify-center w-14 h-16">
            RT
          </div>
          <div class="flex-grow px-4">
            <div class="text-xl">{{$apiTimeDay}}s</div>
            <div class="text-gray-500 text-xs">responding speed</div>
          </div>
          <div class="flex-none px-4 text-right">
            <div
              class="text-sm flex items-center justify-end {{$apiTimeTrend === 2 ? 'text-red-600' : ''}} {{$apiTimeTrend === 1 ? 'text-green-600' : ''}} {{$apiTimeTrend === 0 ? 'text-blue-600' : ''}} ">
              <div>{{$apiTimeRate}}%</div>
              @if($apiTimeTrend === 1)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 " fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
              @endif
              @if($apiTimeTrend === 2)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
              @endif
              @if($apiTimeTrend === 0)
                <div class="ml-1"> -</div>
              @endif
            </div>
            <div class="text-gray-500 text-xs">7 days percentage</div>
          </div>
        </div>
        <div class=" flex shadow bg-white  dark:bg-blackgray-4 rounded items-center">
          <div
            class="flex-none rounded-l bg-yellow-600 text-white text-xl flex items-center justify-center w-14 h-16">
            FN
          </div>
          <div class="flex-grow px-4">
            <div class="text-xl">{{$fileNumDay ?: 0}}</div>
            <div class="text-gray-500 text-xs">number of files</div>
          </div>
          <div class="flex-none px-4 text-right">
            <div
              class="text-sm flex items-center justify-end {{$fileNumTrend === 1 ? 'text-red-600' : ''}} {{$fileNumTrend === 2 ? 'text-green-600' : ''}} {{$fileNumTrend === 0 ? 'text-blue-600' : ''}} ">
              <div>{{$fileNumRate}}%</div>
              @if($fileNumTrend === 2)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 " fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
              @endif
              @if($fileNumTrend === 1)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
              @endif
              @if($fileNumTrend === 0)
                <div class="ml-1"> -</div>
              @endif
            </div>
            <div class="text-gray-500 text-xs">7 days percentage</div>
          </div>
        </div>
        <div class=" flex shadow bg-white  dark:bg-blackgray-4 rounded items-center">
          <div
            class="flex-none rounded-l bg-green-600 text-white text-xl flex items-center justify-center w-14 h-16">
            LN
          </div>
          <div class="flex-grow px-4">
            <div class="text-xl">{{$logNumDay}}</div>
            <div class="text-gray-500 text-xs">Operation log</div>
          </div>
          <div class="flex-none px-4 text-right">
            <div
              class="text-sm flex items-center justify-end {{$logNumTrend === 1 ? 'text-red-600' : ''}} {{$logNumTrend === 2 ? 'text-green-600' : ''}} {{$logNumTrend === 0 ? 'text-blue-600' : ''}} ">
              <div>{{$logNumRate}}%</div>
              @if($fileNumTrend === 2)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 " fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
              @endif
              @if($logNumTrend === 1)
                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
              @endif
              @if($logNumTrend === 0)
                <div class="ml-1"> -</div>
              @endif
            </div>
            <div class="text-gray-500 text-xs">7 days percentage</div>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-2 gap-5 whitespace-nowrap mt-5">
        <div class=" shadow bg-white dark:bg-blackgray-4 rounded p-5">
          {!! $apiNumChart !!}
        </div>
        <div class=" shadow bg-white dark:bg-blackgray-4 rounded p-5">

          {!! $apiTimeChart !!}
        </div>
        <div class=" shadow bg-white dark:bg-blackgray-4 rounded p-5">
          {!! $fileNumChart !!}
        </div>
        <div class=" shadow bg-white dark:bg-blackgray-4 rounded p-5">
          {!! $logNumChart !!}
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mt-5">
        <div class="bg-white dark:bg-blackgray-4 rounded shadow p-5">
          <div class="flex items-center relative">
            <span class="text-base flex-grow">Visit Rank</span>
            <div class="flex-none select-none">
              <a-dropdown>
                <a-button>Last @{{filter.views.day}} days</a-button>
                <template #content>
                  <a-doption v-for="item in days" @click="handleSelect(item.key, 'views')">@{{item.label}}</a-doption>
                </template>
              </a-dropdown>
            </div>
          </div>
          <app-table class="mt-4"
                     url="{{ route("admin.system.visitorApi.ajax", ['_time' => random_int(1, 10000)]) }}"
                     :columns='columnsTotal'
                     :filter='filter.views'
                     :limit="10"
                     :simple="true"
                     :n-params='table'></app-table>
        </div>
        <div class="bg-white dark:bg-blackgray-4 rounded shadow p-5">
          <div class="flex items-center relative">
            <span class="text-base flex-grow">Operation log</span>
            <div class="flex-none select-none">
              <a-dropdown>
                <a-button>Last @{{filter.log.day}} days</a-button>
                <template #content>
                  <a-doption v-for="item in days" @click="handleSelect(item.key, 'log')">@{{item.label}}</a-doption>
                </template>
              </a-dropdown>
            </div>
          </div>
          <app-table class="mt-4"
                     url="{{ route("admin.system.operate.ajax", [ '_time' => random_int(1, 10000)]) }}"
                     :columns='columnsLog'
                     :filter='filter.log'
                     :limit="11"
                     :simple="true"
                     :n-params='table'></app-table>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
  export default {
    data: function () {

      return {
        days: [
          {
            label: 'last 3 days',
            key: 3
          },
          {
            label: 'last 7 days',
            key: 7
          },
          {
            label: 'last 30 days',
            key: 30
          },
        ],
        filter: {
          views: {
            day: 3
          },
          response: {
            day: 3
          },
          log: {
            day: 3
          },
        },
        table: {"vBind:row-key": "row => row.excel_id", bordered: false},
        columnsTotal: [
          {
            title: 'interface',
            dataIndex: 'name',
            'render:rowData': [
              {
                nodeName: 'div',
                class: 'flex gap-2 items-center',
                child: [
                  {
                    nodeName: 'n-tag',
                    size: 'small',
                    type: 'info',
                    child: '@{{rowData.record.method}}'
                  },
                  {
                    nodeName: 'div',
                    child: '@{{rowData.record.desc}}'
                  },
                ]
              },
              {
                nodeName: 'div',
                class: 'text-gray-400',
                child: '@{{rowData.record.name}}'
              },
            ]
          },
          {
            title: 'Views',
            dataIndex: 'pv',
            sorter: true
          },
          {
            title: 'visitor',
            dataIndex: 'uv',
            align: 'right',
            sorter: true
          }
        ],
        columnsResponse: [
          {
            title: 'response',
            dataIndex: 'name',
            'render:rowData, rowIndex': [
              {
                nodeName: 'div',
                class: 'flex gap-2 items-center',
                child: [
                  {
                    nodeName: 'n-tag',
                    size: 'small',
                    type: 'info',
                    child: '@{{rowData.record.method}}'
                  },
                  {
                    nodeName: 'div',
                    child: '@{{rowData.record.desc}}'
                  },
                ]
              },
              {
                nodeName: 'div',
                class: 'text-gray-400',
                child: '@{{rowData.record.name}}'
              },
            ]
          },
          {
            title: 'maximum response',
            dataIndex: 'max',
            sorter: true
          },
          {
            title: 'minimal response',
            dataIndex: 'min',
            align: 'right',
            sorter: true
          }
        ],
        columnsLog: [
          {
            title: 'interface',
            dataIndex: '',
            'render:rowData': [
              {
                nodeName: 'div',
                child: '@{{rowData.record.desc}}'
              },
              {
                nodeName: 'div',
                class: 'text-gray-400',
                child: '@{{rowData.record.name}}'
              },
            ]
          },
          {
            title: 'type',
            dataIndex: 'method',
          },
          {
            title: 'time',
            align: 'right',
            'render:rowData': [
              {
                nodeName: 'div',
                child: '@{{rowData.record.created_at}}'
              },
              {
                nodeName: 'div',
                class: 'text-gray-400',
                child: '@{{rowData.record.time}}'
              },
            ]
          }
        ]
      }
    },
    mounted() {


    },
    methods: {
      optionDay: function (name) {
        return [
          {
            label: 'last 3 days',
            key: 3
          },
          {
            label: 'last 7 days',
            key: 7
          },
          {
            label: 'last 30 days',
            key: 30
          },
        ]
      },
      handleSelect(key, name) {
        this.filter[name].day = key
      }
    },
    render() {

    }
  }
</script>
