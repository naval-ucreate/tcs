<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row stacked">
              <div class="col-md-6">
                <div class="input-group push-down-10"></div>

                <div class="pull-right">
                  <div class="btn-group">
                    <date-picker
                      v-model="date"
                      :lang="lang"
                      valuetype="format"
                      :first-day-of-week="1"
                    ></date-picker>
                  </div>
                </div>
              </div>
              <div class="col-md-6"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <div id="columnchart_values"></div>
      </div>
      <div class="col-md-2"></div>
    </div>
  </div>
</template>


<script>
import { GChart } from "vue-google-charts";
import DatePicker from "vue2-datepicker";
export default {
  name: "Report",
  data: function() {
    return {
      date: new Date(),
      active_url: window.location.pathname.split("/"),
      board_id: this.getBoardId,
      chartData: [
        ["Year", "Sales", "Expenses", "Profit"],
        ["2014", 1000, 400, 200]
      ],
      datacart: [
        ["Element", "Density", { role: "style" }],
        ["Copper", 8.94, "#b87333"],
        ["Silver", 10.49, "silver"],
        ["Gold", 19.3, "gold"],
        ["Platinum", 21.45, "color: #e5e4e2"]
      ],
      chartOptions: {
        chart: {
          title: "Report of Project",
          width: 600,
          height: 400,
          bar: { groupWidth: "95%" },
          legend: { position: "none" }
        }
      },
      lang: {
        placeholder: {
          date: "Select Date",
          days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
          months: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
          ]
        }
      }
    };
  },

  created: function() {
    this.initGraph();
    this.axios
      .get("/report/" + this.getBoardId)
      .then(data => {
        if (data.data) {
          this.datacart = data.data;
          this.datacart.unshift(["Element", "Density", { role: "style" }]);
          this.initGraph();
        }
      })
      .catch(err => {});
  },
  components: {
    GChart,
    DatePicker
  },
  computed: {
    getBoardId: function() {
      return this.active_url[this.active_url.length - 1];
    }
  },
  methods: {
    initGraph: function() {
      google.charts.load("current", { packages: ["corechart"] });
      google.charts.setOnLoadCallback(drawChart);
      let chartData = this.datacart;
      function drawChart() {
        var data = google.visualization.arrayToDataTable(chartData);
        var view = new google.visualization.DataView(data);
        view.setColumns([
          0,
          1,
          {
            calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation"
          },
          2
        ]);

        var options = {
          title: "Report of Project",
          width: "100%",
          height: 500,
          bar: { groupWidth: "95%" },
          legend: { position: "none" }
        };
        var chart = new google.visualization.ColumnChart(
          document.getElementById("columnchart_values")
        );
        chart.draw(view, options);
      }
    }
  }
};
</script>
