<template>
  <app-layout :form="true" :formLoading="saveing" :submit="onSubmit">
    <div class="h-full flex flex-grow h-10">
      <div class="flex-none w-56 bg-gray-100 ">
        <ul class="app-package flex flex-col gap-4 p-4">
          <li class="cursor-pointer bg-white shadow hover:shadow-md rounded"
              v-for="(item, index) in formPackage" @click="addFormPackage(index, $event)">
            <div class="flex items-center p-4 gap-4">
              <div class="flex-none">
                <div v-html="item.icon" class="w-6 h-6 flex items-center"></div>
              </div>
              <div class="flex-grow text-right">@{{ item.name }}</div>
            </div>
          </li>
        </ul>
      </div>

      <div class="bg-gray-100 flex-grow">

        <div class="flex justify-center m-4 items-center p-6 bg-white shadow hover:shadow-md rounded" v-if="!formData.length">
          <div class="text-base text-gray-500 flex flex-col justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.54 0 A3.3 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <div class="mt-4">Please click the tool on the left to add an element</div>
          </div>
        </div>

        <ul v-if="formData.length" class="p-4 pl-2">
          <draggable v-model="formData" @start="drag=true" @end="drag=false" class="flex flex-col gap-4">
            <template #item="{element, index}">
              <div class="bg-white shadow hover:shadow-md rounded"
                   :class="{'border-blue-900' : formItemActive === index}"
              >
                <div class="flex items-center p-4 gap-4">
                  <div v-html="formPackage[element.type].icon" class="flex-none w-8 h-8 flex items-center"></div>

                  <div class="flex-grow">
                    <div class="text-base mb-2">@{{ element.name }} <span class="ml-4 text-gray-400"># @{{ element.field }}</span>
                    </div>
                    <n-tag size="small">@{{formPackage[element.type].name}}</n-tag>
                  </div>

                  <div class="flex-none flex gap-4">
                    <a-button type="primary" size="small" @click="editForm(index, $event)">edit</a-button>
                    <a-button size="small" @click="delForm(index)">Delete</a-button>
                  </div>
                </div>
              </div>
            </template>
          </draggable>
        </ul>
      </div>
    </div>
    <a-modal :visible="dialog" title="Edit element" :unmount-on-close="true" @cancel="dialog = false; formItem = {}" @ok="saveForm">
      <a-form direction="vertical" :model="formItem">
        <a-form-item field="name" label="Display name">
          <a-input placeholder="Display name, such as title" v-model="formItem.name"/>
        </a-form-item>
        <a-form-item field="field" label="field name">
          <a-input placeholder="Database field name, such as title" v-model="formItem.field"/>
        </a-form-item>
        <a-form-item field="field" label="List display">
          <a-switch v-model="formItem.list"/>
        </a-form-item>
        <template v-if="formPackage[formItem.type]" v-for="item in formPackage[formItem.type].options">
          <a-form-item v-if="item.type === 'list'" field="data[item.field]" :label="item.name">
            <app-dynamic-data v-model:value="formItem.data[item.field]"/>
          </a-form-item>
          <a-form-item v-if="item.type === 'textarea'" field="data[item.field]" :label="item.name">
            <a-textarea v-model="formItem.data[item.field]"/>
          </a-form-item >
          <a-form-item v-if="item.type === 'text'" field="data[item.field]" :label="item.name">
            <a-input v-model="formItem.data[item.field]"/>
          </a-form-item>
          <a-form-item v-if="item.type === 'radio'" field="data[item.field]" :label="item.name">
            <a-radio-group v-model="formItem.data[item.field]">
              <a-radio v-for="(value, key) in item.data" :value="key">
                @{{ value }}
              </a-radio>
            </a-radio-group>
          </a-form-item>
        </template>
      </a-form>
    </a-modal>
  </app-layout>
</template>

<script>
  const defaultData = @json($info->data ? $info->data : []);

  const formPackage = {
    text: {
      name: 'text box',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill= "white" fill-opacity="0.01"/><rect x="6" y="6" width="36" height="36" rx="3" fill="none" stroke="#333" stroke-width="3" stroke-linejoin="round"/><path d="M16 19V16H32V19" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 34H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 18L24 34" stroke ="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'text',
      options: [
        {
          name: 'Authentication',
          field: 'required',
          type: 'radio',
          data: [
            'optional',
            'required'
          ]
        },
        {
          name: 'type',
          field: 'type',
          type: 'radio',
          data: {
            'text': 'text',
            'textarea': 'Multi-line text',
            'number': 'Number',
            'password': 'password',
          }
        }
      ],
      data: {
        required: 0,
        type: 'text'
      }
    },
    select: {
      name: 'drop down selection',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill= "white" fill-opacity="0.01"/><path d="M40 28L24 40L8 28" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/ ><path d="M8 10H40" stroke="#333" stroke-width="3" stroke-linecap="round"/><path d="M8 18H40" stroke="#333" stroke-width=" 3" stroke-linecap="round"/></svg>',
      field: 'select',
      options: [{
        name: 'drop-down options',
        field: 'options',
        type: 'list',
      }],
      data: {
        options: []
      }
    },
    radio: {
      name: 'single option',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r= "20" fill="none" stroke="#333" stroke-width="3"/><circle cx="24" cy="24" r="8" fill="none" stroke="#333 " stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'radio',
      options: [{
        name: 'single option',
        field: 'options',
        type: 'list',
      }],
      data: {
        options: [
          'Option one',
          'Option two',
        ]
      }
    },
    checkbox: {
      name: 'Multiple options',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M39 6H9C7.34315 6 6 7.34315 6 9V39C6 40.6569 7.34315 42 9 42H39C40.6569 42 42 40.6569 42 39V9C42 7.34315 40.6569 6 39 6Z" fill="none" stroke="#333" stroke-width="3"/><path d="M32 16H16V32H32V16" " stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'checkbox',
      options: [{
        name: 'Multiple options',
        field: 'options',
        type: 'list',
      }],
      data: {
        options: [
          'Option one',
          'Option two'
        ]
      }
    },
    image: {
      name: 'image upload',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M38 21V40C38 41.1046 37.1046 42 36 42H8C6.89543 42 6 41.1046 6 40V12C6 10.8954 6.89543 10 8 10H26.3636" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>< path d="M12 31.0308L18 23L21 26L24.5 20.5L32 31.0308H12Z" fill="none" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke- linejoin="round"/><path d="M34 10H42" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/ ><path d="M37.9941 5.79544V13.7954" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
      field: 'image',
      options: [
        {
          name: 'Authentication',
          field: 'required',
          type: 'radio',
          data: [
            'optional',
            'required'
          ]
        },
        {
          name: 'Upload method',
          field: 'type',
          type: 'radio',
          data: [
            'File Manager',
            'Local Upload'
          ]
        },
      ],
      data: {
        required: 0,
        type: 0,
      }
    },
    images: {
      name: 'Multiple image upload',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill= "white" fill-opacity="0.01"/><rect x="4" y="6" width="40" height="30" rx="2" fill="none"/><rect x= "4" y="6" width="40" height="30" rx="2" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" /><path d="M20 42H28" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M34 42H36" stroke=" #333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 42H6" stroke="#333" stroke-width="3" stroke-linecap ="round" stroke-linejoin="round"/><path d="M42 42H44" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/> <path d="M12 42H14" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'images',
      options: [
        {
          name: 'Authentication',
          field: 'required',
          type: 'radio',
          data: [
            'optional',
            'required'
          ]
        },
        {
          name: 'Upload method',
          field: 'type',
          type: 'radio',
          data: [
            'File Manager',
            'Local Upload'
          ]
        },
        {
          name: 'Number of pictures',
          field: 'num',
          type: 'text',
        },
      ],
      data: {
        type: 0,
        required: 0,
        num: 5
      }
    },
    file: {
      name: 'File upload',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill= "white" fill-opacity="0.01"/><path d="M11.6777 20.271C7.27476 21.3181 4 25.2766 4 30C4 35.5228 8.47715 40 14 40C14.9474 40 15.864 39.8683 16.735 stroke="3" stroke width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M36.0547 20.271C40.4577 21.3181 43.7324 25.2766 43.7324 30C43.7324 35.5228 39.2553 40 33.7324 40V40C32.785 40 31.8684 39.8683 30.9999 39.6221" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M36 20C36 13.3726 30.6274 8 24 8C17.3726 8 12 13.3726 12 20" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.0654 27.881L23.9999 20.9236L31.1318 28" stroke ="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 38V24.4618" stroke="#333" stroke-width="3 " stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'file',
      options: [
        {
          name: 'Authentication',
          field: 'required',
          type: 'radio',
          data: [
            'optional',
            'required'
          ]
        },
        {
          name: 'Upload method',
          field: 'type',
          type: 'radio',
          data: [
            'File Manager',
            'Local Upload'
          ]
        },
      ],
      data: {
        type: 0,
        required: 0,
      }
    },
    date: {  file: {
      name: '文件上传',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><path d="M11.6777 20.271C7.27476 21.3181 4 25.2766 4 30C4 35.5228 8.47715 40 14 40C14.9474 40 15.864 39.8683 16.7325 39.6221" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M36.0547 20.271C40.4577 21.3181 43.7324 25.2766 43.7324 30C43.7324 35.5228 39.2553 40 33.7324 40V40C32.785 40 31.8684 39.8683 30.9999 39.6221" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M36 20C36 13.3726 30.6274 8 24 8C17.3726 8 12 13.3726 12 20" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.0654 27.881L23.9999 20.9236L31.1318 28" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 38V24.4618" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'file',
      options: [
        {
          name: '验证',
          field: 'required',
          type: 'radio',
          data: [
            '选填',
            '必填'
          ]
        },
        {
          name: '上传方式',
          field: 'type',
          type: 'radio',
          data: [
            '文件管理器',
            '本地上传'
          ]
        },
      ],
      data: {
        type: 0,
        required: 0,
      }
    },
    date: {
      name: '日期时间',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><rect x="4" y="4" width="40" height="40" rx="2" fill="none" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14H44" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><line x1="44" y1="11" x2="44" y2="23" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 22H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 22H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 22H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 29H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 29H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 29H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 36H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 36H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 36H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><line x1="4" y1="11" x2="4" y2="23" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'date',
      options: [
        {
          name: '验证',
          field: 'required',
          type: 'radio',
          data: [
            '选填',
            '必填'
          ]
        },
        {
          name: '类型',
          field: 'type',
          type: 'radio',
          data: {
            'date': '日期',
            'time': '时间',
            'datetime': '日期时间',
            'range': '时间范围',
          }
        }
      ],
      data: {
        required: 0,
        type: 'date'
      }
    },
      name: 'datetime',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill= "white" fill-opacity="0.01"/><rect x="4" y="4" width="40" height="40" rx="2" fill="none" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14H44" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><line x1="44" y1="11" x2="44" y2="23" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 22H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 22H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 22H36"stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 29H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 29H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d= " M32 29H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 36H16" stroke="#333" stroke-width= "3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 36H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin ="round"/><path d="M32 36H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><line x1="4" y1="11" x2="4" y2="23" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'date',
      options: [
        {
          name: 'Authentication',
          field: 'required',
          type: 'radio',
          data: [
            'optional',
            'required'
          ]
        },
        {
          name: 'type',
          field: 'type',
          type: 'radio',
          data: {
            'date': 'date',
            'time': 'time',
            'datetime': 'datetime',
            'range': 'Time range',
          }
        }
      ],
      data: {
        required: 0,
        type: 'date'
      }
    },
    editor: {
      name: 'Editor',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="6" width= "36" height="36" rx="3" fill="none" stroke="#333" stroke-width="3"/><path d="M14 16L18 32L24 19L30 32L34 16" stroke="#333 " stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'editor',
      options: [{
        name: 'Authentication',
        field: 'required',
        type: 'radio',
        data: [
          'optional',
          'required'
        ]
      },],
      data: {
        required: 0,
      }
    },
    color: {
      name: 'color selection',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill= "white" fill-opacity="0.01"/><path fill-rule="evenodd" clip-rule="evenodd" d="M37 37C39.2091 37 41 35.2091 41 33C41 31.5272 39.6667 29.5272 37 27C34.3333 29.5272 33 31.5272 33 33C33 35.2091 34.7909 37 37 37Z" fill="#333"/><path d="M20.8535 5.50439L24.389 9.03993" stroke="#333" stroke-width="3" stroke-linecap="round" /><path d="M23.6818 8.33281L8.12549 23.8892L19.4392 35.2029L34.9955 19.6465L23.6818 8.33281Z" stroke="#333" stroke-width="3" stroke-linejoin="round"/ ><path d="M12 20.0732L28.961 25.6496" stroke="#333" stroke-width="3" stroke-linecap="round"/><path d="M4 43H44" stroke="#333" stroke-width="3" stroke-linecap="round"/></svg>',
      field: 'color',
      options: [
        {
          name: 'type',
          field: 'type',
          type: 'radio',
          data: {
            'color': 'Option color',
            'picker': 'Free color',
          }
        }],
      data: {
        type: 'color'
      }
    }
  };

  export default {
    data() {
      return {
        dialog: false,
        drag: false,
        saveing: false,
        formData: defaultData ? defaultData : [],
        formItemActive: false,
        formPackage: formPackage,
        formItem: {},
      }
    },
    methods: {
      addFormPackage(index) {
        this.formData.push(JSON.parse(JSON.stringify({
          type: index,
          name: this.formPackage[index].name,
          field: this.formPackage[index].field,
          data: this.formPackage[index].data,
          list: 1,
        })))
      },
      editForm(index, e) {
        this.dialog = true
        this.formItemActive = index
        this.formItem = JSON.parse(JSON.stringify(this.formData[index]))
      },
      saveForm() {
        this.formData[this.formItemActive] = this.formItem
        this.dialog = false
      },
      delForm(index) {
        this.formData.splice(index, 1)
      },
      addFormOptions(options, value) {
        options.push(value || '');
      },
      delFormOptions(options, key) {
        options.splice(key, 1)
      },
      onSubmit() {
        let fields = [];
        for (let i in this.formData) {
          let item = this.formData[i]
          if (!item.field) {
            window.message.error('表单元素未设置字段名')
            return false
          }

          if (fields.length && fields.includes(item.field)) {
            window.message.error('表单元素字段名有重复')
            return false
          }
          fields.push(item.field)
        }
        this.saveing = true

        window.ajax({
          url: "{{route('admin.tools.form.setting.save', ['id' => $id], true)}}",
          method: 'post',
          successMsg: true,
          data: {
            data: this.formData
          }
        }).then((res) => {
          this.saveing = false
        }).catch((error) => {
          this.saveing = false
        })
      }
    }
  }
</script>
