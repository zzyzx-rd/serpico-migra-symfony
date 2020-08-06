import $ from 'jquery';
import * as Survey from 'survey-jquery';
import json from './survey_test';
import '../css/survey.css';

$.ajax({
  method: "GET",
  url: url,
  success: function (data) {
    console.log(data);
    console.log(data.substring(5175,5225));
    window.survey = new Survey.Model(json);
    survey.data
      = {
      title_survey: 'John Doe',
      question:'Salut a tous c est David Lafarge',
    };
    $("#surveyElement").Survey({model: survey});
  }
});

/*
async function main() {
  const data = await $.getJSON(url);
  console.log(data);
  console.log(data.substring(5175, 5225));
  const survey = new Survey.Model(data);
  $("#surveyElement").Survey({model: survey});
}

main();
*/
