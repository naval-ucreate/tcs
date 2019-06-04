<template>
  <div class="content-frame-body listing_view">
    <div v-show="is_config" class="hook-alert">No Configurations set for {{list_item[active_list]}}</div>

    <div class="row">
      <div class="col-md-4" style="padding-top:50px"></div>
      <div class="col-md-4" style="padding-top: 50px;">
        <div class="configration">
          <div class="config-alert">Configurations</div>
          <ul class="nav nav-tabs nav-justified">
            <li
              v-for="(single_list, i) in list_item"
              v-bind:class=" i == active_list ? 'active': ''"
              @click="updateList(i)"
              :key="i"
            >
              <a href="#" data-toggle="tab" aria-expanded="false">{{single_list}}</a>
            </li>
          </ul>
        </div>

        <Loader v-show="is_loading"></Loader>

        <div class="panel panel-default" id="checklist">
          <div class="panel-heading ui-draggable-handle">
            <h3 class="panel-title text-center">{{board_name}}</h3>
          </div>

          <div
            class="panel-body list-group list-group-contacts"
            v-for="list in lists"
            :key="list.id"
          >
            <a
              href="javascript:void(0)"
              class="list-group-item"
              :class="list['is_enable']?'active':''"
            >
              <span class="enable_report" rel="1" @click="config(list)">
                <div
                  class="list-group-status status-checklist"
                  :class="list['is_enable']?'status-online':'status-offline'"
                ></div>
                <h2 class="contacts-title" style="margin-top: 10px;">{{list['name']}}</h2>
                <div class="list-group-controls _checkbox">
                  <i v-show="list['is_enable']" class="fa fa-check checklist" aria-hidden="true"></i>
                  <input type="hidden" value="1" name="hook_checked">

                  <input style="display:none;" type="radio" name="list_id" :value="list['id']">
                </div>
              </span>
              <span v-if="list['is_enable']" class="list-group-controls" :id="list['id']">
                <button
                  class="btn btn-danger btn-sm delete-hook _checklist"
                  @click="removeConfig(list)"
                  data="1"
                  rel="something"
                >Remove</button>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <Modal v-if="show_model" @close="show_model=false">
      <template slot="header">Configurations</template>
      <template slot="body">
        <label for="email">Lable Name:</label>
        <input
          type="text"
          class="form-control"
          placeholder="Enter lable name "
          v-model="lable_name"
        >
        <hr>
        <label for="email">Lable Color</label>
        <input type="color" class="form-control" placeholder="Choice color" v-model="color_code">
        <hr>
        <label for="email">Check ListType:</label>
        <select class="form-control" v-model="check_list_type">
          <option value=" ">--Please select--</option>
          <option value="0">Checklist exists</option>
          <option value="1">Checklist complete</option>
        </select>
      </template>
      <div slot="footer">
        <button class="btn btn-info" @click="save()">Save</button>
        <button @click="show_model=false" class="btn btn-danger">Cancel</button>
      </div>
    </Modal>
  </div>
</template>


<script>
import modal from "./ modal";
import Loader from "./loader";
export default {
  nameL: "List",
  data: function() {
    return {
      lists: [],
      list_item: ["Checklist", "Pm", "Qa", "Dev", "Production"],
      active_list: 0,
      is_config: true,
      active_url: window.location.pathname.split("/"),
      final_list: [],
      selected_list: {},
      show_model: false,
      lable_name: "",
      is_loading: true,
      color_code: "#e53333",
      check_list_type: " ",
      image: "",
      board_name: ""
    };
  },
  components: {
    Modal: modal,
    Loader: Loader
  },
  created: function() {
    this.axios
      .get("/listapi/" + this.getBoardId())
      .then(data => {
        this.is_loading = false;
        this.final_list = data.data;
        this.lists = data.data;
        this.refecterList(1);
        this.image = this.lists[0].board["background_image"];
        this.board_name = this.lists[0].board["name"];
      })
      .catch(err => {
        this.is_loading = false;
        console.log("some thing went wrong");
      });
  },
  methods: {
    updateList: function(i) {
      this.active_list = i;
      this.refecterList(i + 1);
    },
    getBoardId: function() {
      return this.active_url[this.active_url.length - 1];
    },
    refecterList: function(type) {
      this.lists = this.final_list;
      let count = 0;
      this.lists.filter((value, key) => {
        this.lists[key].is_enable = this.checkEnable(
          value.id,
          value.board_config,
          type
        );
        if (this.lists[key].is_enable) {
          count++;
        }
      });
      if (count > 0) {
        this.is_config = false;
      } else {
        this.is_config = true;
      }
    },
    checkEnable: function(list_id, board_config, type) {
      if (board_config.length > 0) {
        let data = board_config.filter(
          data => data.list_id == list_id && data.type == type && data.status
        );
        if (data.length > 0) {
          return true;
        }
      }
      return false;
    },
    config: function(list) {
      console.log(this.active_list);
      if (this.active_list == 0) {
        this.show_model = true;
        this.selected_list = list;
        return false;
      }
      this.addConfig(list);
    },
    update: function() {
      this.lists = this.final_list;
    },
    addConfig: function(list, additional = {}) {
      this.is_loading = true;
      let form_data = {
        _token: this.csrf_token,
        board_id: this.getBoardId(),
        type: this.active_list + 1,
        status: true
      };
      if (Object.keys(additional).length > 0) {
        form_data = Object.assign(form_data, additional);
      }
      this.axios
        .put("/config_enable/" + list.id, form_data)
        .then(data => {
          if (data.data) {
            let index_array = this.final_list.indexOf(list);
            this.final_list[index_array].is_enable = true;
            let counts = 0;
            this.final_list[index_array].board_config.forEach(
              (element, key) => {
                if (
                  element.list_id == list.id &&
                  this.active_list + 1 == element.type
                ) {
                  counts++;
                  this.final_list[index_array].board_config[key].status = true;
                }
              }
            );
            if (counts == 0) {
              this.final_list[index_array].board_config.push(data.data);
            }
            this.lists = [];
            this.updateList(this.active_list);
            this.is_loading = false;
            if (Object.keys(additional).length > 0) {
              this.emptyForm();
            }
            swal("Success", "Configration added successfully", "success");
          }
        })
        .catch(err => {
          this.is_loading = false;
          swal("Oh noes!", "Server Request fail", "error");
        });
    },
    emptyForm: function() {
      this.lable_name = "";
      this.check_list_type = " ";
      this.show_model = false;
    },
    save: function() {
      if (this.lable_name == "" || this.check_list_type == " ") {
        swal("Error", "All field are rquired", "error");
        return false;
      }
      let additional_fields = {
        lable_name: this.lable_name,
        checklist_type: this.check_list_type,
        lable_color: this.color_code
      };
      this.addConfig(this.selected_list, additional_fields);
    },
    removeConfig: function(list) {
      swal({
        title: "Are you sure want to remove configration?",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          this.is_loading = true;
          this.axios
            .put("/disable_config/" + list.id, {
              _token: this.csrf_token,
              board_id: this.getBoardId(),
              type: this.active_list + 1,
              status: false
            })
            .then(data => {
              if (data) {
                let index_array = this.final_list.indexOf(list);
                this.final_list[index_array].is_enable = false;
                this.final_list[index_array].board_config.forEach(
                  (element, key) => {
                    if (
                      element.list_id == list.id &&
                      this.active_list + 1 == element.type
                    ) {
                      this.final_list[index_array].board_config[
                        key
                      ].status = false;
                    }
                  }
                );
                this.lists = [];
                this.updateList(this.active_list);
                this.is_loading = false;
                swal("Success", "Configration updated successfully", "success");
              }
            })
            .catch(err => {
              this.is_loading = false;
              swal("Oh noes!", "Server Request fail", "error");
            });
        } else {
          swal("Request cancel", "error");
        }
      });
    }
  }
};
</script>
