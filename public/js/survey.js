import $ from 'jquery';
import * as Survey from 'survey-jquery';
import json from './survey_test';
import '../css/survey.css';

const survey = new Survey.Model(json);

var myCss = {
  navigation:{
    complete:"btn sq-button-custom"
  },
  paneldynamic:{
    buttonRemove:"btn removeBtn",
    buttonAdd:"btn",
  },
  dropdown:{
    control:"dropdown-trigger dropdown",
  },  
};

Survey
    .StylesManager
    .applyTheme("bootstrap");

survey.onComplete.add(result => {
  const json = JSON.stringify(result.data);
  console.log(json);
  function spliturl() {
    var url = window.location.href;
    var table = url.split("/");
    console.log(table[table.length - 1]);
    var nb = table[table.length - 1];
    nb = parseInt(nb);
    console.log(typeof nb);
    return nb;
  }
  $.ajax({
    method: "POST",
    url: url,
    data: { data: json, id: spliturl() },
    success: function () {
    }
  });
});

survey
    .onUpdateQuestionCssClasses
    .add(function (survey, options) {
        var classes = options.cssClasses
        classes.root = "sq-root-custom";
        classes.navigation = "sq-button-custom";
    });

$("#surveyElement").Survey({ model: survey, css: myCss});
