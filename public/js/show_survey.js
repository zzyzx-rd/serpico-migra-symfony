import * as Survey from 'survey-jquery';
import $ from "jquery";

$.ajax({
  method: "GET",
  url: url,
  success: function (data) {
    console.log(data);
    console.log(data[658]+data[659]+data[660]+data[661]+data[662]+data[663]);
    window.survey = new Survey.Model(data);
    $("#surveyElement").Survey({model: survey});
  }
});
