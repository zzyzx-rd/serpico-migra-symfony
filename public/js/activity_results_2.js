Chart.defaults.global.defaultFontFamily = 'Roboto';
Chart.defaults.global.defaultFontColor = '#000';

const colors = [
  '#3366cc',
  '#dc3912',
  '#ff9900',
  '#109618',
  '#990099',
  '#0099c6',
  '#dd4477',
  '#66aa00'
];

function generateDistChart(rawDistChartData, crtType, distAvgResult, crtIndex) {
  const distGraphDataLength = rawDistChartData && rawDistChartData.length;
  if (
    crtType === 3 ||
    !distGraphDataLength
  ) return;

  const distChartData = {
    labels: [],
    datasets: []
  }

  const firstDataRow = rawDistChartData[1];
  if (!firstDataRow) return;

  const firstDataRowLength = firstDataRow.length;
  const lineParticipants = [ 'Moy. phases', `Res. global (${distAvgResult})` ];
  const participants = [
    ...rawDistChartData[0].map(e => e ? e.label : null).filter(e => e),
    ...lineParticipants
  ];
  const participantsLength = participants.length;

  /*
  * We start off with the first data row of "rawDistChartData",
  * iterating through it to initialize the datasets
  */
  let actualIndex = 0;
  let firstCriterion;
  for (let i = 0; i < firstDataRowLength; ++i) {
    let rawValue = firstDataRow[i];
    if (i === 0) {
      // value is the criterion, push it to global label array
      distChartData.labels.push(firstCriterion = rawValue);
      continue;
    }

    // value is a percentage number and we process it as useful data
    if ('number' === typeof rawValue) {
      const datasetLabel = participants[actualIndex];
      const datasetColor = colors[actualIndex];
      // should the value be represented in line form?
      // (avg of stages / global result)
      const isLineInChart = lineParticipants.includes(datasetLabel);

      let value = rawValue * 100;
      value = value.toFixed(2);

      distChartData.datasets.push({
        type: isLineInChart ? 'line' : null,
        pointRadius: 5,
        pointHoverRadius: 10,
        fill: !isLineInChart,
        backgroundColor: datasetColor,
        borderColor: datasetColor,
        label: datasetLabel,
        data: isLineInChart ? [ { x: value, y: firstCriterion } ] : [ value ]
      });
      ++actualIndex;
    }
  }
  /*
    * end for
    */

  /*
    * Then we iterate from 2 to length-1 to fetch
    * the rest of the data
    */
  for (const row of rawDistChartData.slice(2)) {
    const rowLength = row.length;
    let criterion;
    let actualIndex = 0;
    for (let i = 0; i < rowLength; ++i) {
      let rawValue = row[i];
      if (i === 0) {
        // value is stage or user, push it to global labels
        distChartData.labels.push(criterion = rawValue);
      }

      // should the value be represented in line form?
      // (avg of stages / global result)
      const isGlobalResult = actualIndex === participantsLength - 1
      const isStagesAvg = actualIndex === participantsLength - 2
      const isLineInChart = isStagesAvg || isGlobalResult;

      // let's double check, just for good measure
      if ('number' === typeof rawValue) {
        let value = rawValue * 100;
        value = value.toFixed(2);

        distChartData.datasets[actualIndex] && distChartData.datasets[actualIndex].data.push(
          isLineInChart ? { x: value, y: criterion } : value
        );
        ++actualIndex;
      }
    }
  }
  /*
   * end for
   */

  const datasets = distChartData.datasets;
  const datasetsLength = datasets.length;
  if (datasetsLength === 2) {
    datasets[0].type = null;
  }

  // some quick sorting so the lines don't go behind the bars, literally
  distChartData.datasets = distChartData.datasets.sort(
    (_a, b) => b.type ? 1 : -1
  );

  console.log(crtIndex);

  const distWeather = generateWeather(distChartData, crtIndex == -2);


  /**
   * woop woop! Hereby we instantiate the actual thing
   */
  const distChart = new Chart($('#distribution-chart'), {
    type: 'horizontalBar',
    data: distChartData,
    options: {
      elements: {
        line: {
          tension: 0
        }
      },
      scales: {
        xAxes: [{
          ticks: {
            min: 0,
            max: 100
          }
        }]
      },
      plugins: {
        datalabels: {
          labels: {
            title: null
          }
        }
      }
    }
  });

  return { distChart, distWeather };
}

/**
 * @param {[[]]} rawPerfChartData
 * @param {number} crtType
 * @param {number} lowerbound
 * @param {number} upperbound
 * @param {[ number, number ]} graphParameters
 */
function generatePerfChart(rawPerfChartData, crtType, lowerbound, upperbound, graphParameters) {
  const perfGraphDataLength = rawPerfChartData && rawPerfChartData.length;
  if (!perfGraphDataLength) return;

  const perfChartData = {
    labels: [],
    datasets: []
  }

  // yes/no
  if (crtType == 3) {
    const labels = [];
    const yes = {
      label: 'Yes',
      backgroundColor: '#3366cc',
      barPercentage: 2 / 3,
      data: []
    };
    const no = {
      label: 'No',
      backgroundColor: '#dc3912',
      barPercentage: 2 / 3,
      data: []
    };

    for (const row of rawPerfChartData.slice(1)) {
      const yesRatio = row[1] * 100;
      labels.push(row[0]);
      yes.data.push(yesRatio);
      no.data.push(100 - yesRatio);
    }


    /**
     * woop woop! Hereby we instantiate the actual thing
     */
    return new Chart($('#performance-chart'), {
      type: 'horizontalBar',
      data: { labels, datasets: [ yes, no ] },
      options: {
        events: null,
        scales: {
          xAxes: [{
            stacked: true,
            ticks: {
              min: 0,
              max: 100
            }
          }],
          yAxes: [{ stacked: true }]
        },
        plugins: {
          datalabels: {
            formatter: value => value > 1 ? `${value} %` : null,
            color: '#fff'
          }
        }
      }
    });
  } else {
    const firstDataRow = rawPerfChartData[1];

    if (!firstDataRow) return;

    const firstDataRowLength = firstDataRow.length;
    const globalResult = firstDataRow[firstDataRowLength - 1].toFixed(2);
    const participantsInLabel = rawPerfChartData[0].filter(e => e.label).length;
    const lineParticipants = [ 'Res. indiv', `Res. global (${globalResult}${upperbound == 100 ? ' %' : ''})` ];

    const participants = [
      ...rawPerfChartData[0]
        .map(e => participantsInLabel ? e.label : e)
        .filter(
          e => e && 'string' === typeof e && !(/res (indiv|global)/i.test(e) || /(global )?res/i.test(e))
        )
    ];
    participants.push(...lineParticipants);

    if (!participantsInLabel && !graphParameters.every(e => e >= 0)) {
      // not aggregated view, data structure is completely different
      const datasetLabels = rawPerfChartData.shift().filter(
        e => e && 'string' === typeof e
      );
      const participants = rawPerfChartData.map(
        e => e[0]
      );
      const distances = rawPerfChartData.map(
        e => e.filter(e => 'number' === typeof e).map(e => e.toFixed(2))
      );

      const datasets = [];
      const datasetLabelsLength = datasetLabels.length;
      for (let i = datasetLabelsLength - 1; i >= 0; --i) {
        const isLine = i === datasetLabelsLength - 1 || (
          !datasetLabels.every(e => /res (indiv|global)/i.test(e) || /(global )?res/i.test(e)) && i === datasetLabelsLength - 2
        );

        datasets.push({
          label: datasetLabels[i],
          type: isLine ? 'line' : null,
          data: distances.map(e => e[i]),
          fill: !isLine,
          pointRadius: 5,
          pointHoverRadius: 10,
          borderColor: colors[i],
          backgroundColor: colors[i]
        });
      }

      return new Chart($('#performance-chart'), {
        type: 'bar',
        data: {
          labels: participants,
          datasets
        },
        options: {
          elements: { line: { tension: 0 } },
          scales: {
            yAxes: [
              { ticks: { min: lowerbound, max: upperbound } }
            ]
          },
          plugins: { datalabels: { labels: { title: null } } }
        }
      });
    }

    /*
     * We start off with the first data row of "rawPerfChartData",
     * iterating through it to initialize the datasets
     */
    let actualIndex = 0;
    for (let i = 0; i < firstDataRow.length; ++i) {
      let value = firstDataRow[i];

      const valueIsNull = value === null;

      if (i === 0) {
        // value is the criterion, push it to global label array
        perfChartData.labels.push(firstStageOrUser = value);
      }


      // else value is a percentage number and we process it as useful data

      const datasetLabel = participants[actualIndex];
      if (!datasetLabel) break;
      // should the value be represented in line form?
      const isLineInChart = lineParticipants.includes(datasetLabel);
      const datasetColor = colors[actualIndex];

      if ('number' === typeof value || valueIsNull) {
        value = valueIsNull ? null : value.toFixed(2);

        perfChartData.datasets.push({
          type: isLineInChart ? 'line' : null,
          pointRadius: 5,
          pointHoverRadius: 10,
          fill: !isLineInChart,
          backgroundColor: datasetColor,
          borderColor: datasetColor,
          label: datasetLabel,
          data: [ value ]
        });
        ++actualIndex;
      }
    }
    /*
     * end for
     */


    /*
     * Then we iterate from 2 to length-1 to fetch
     * the rest of the data
     */
    for (const row of rawPerfChartData.slice(2)) {
      const rowLength = row.length;
      let actualIndex = 0;

      for (let i = 0; i < rowLength; ++i) {
        // if (!participants[actualIndex]) break;

        let value = row[i];
        const valueIsNull = value === null;
        if (i === 0) {
          // value is stage or user, push it to global labels
          perfChartData.labels.push(value);
        }

        const currentDataset = perfChartData.datasets[actualIndex];

        if (currentDataset && ('number' === typeof value || valueIsNull)) {
          value = valueIsNull ? null : value.toFixed(2);

          currentDataset.data.push(value);
          ++actualIndex;
        }
      }
    }
    /*
     * end for
     */

    const datasets = perfChartData.datasets;
    const datasetsLength = datasets.length;
    if (datasetsLength === 2) {
      datasets[0].type = null;
    }

    // some quick sorting so the lines don't go behind the bars, literally
    perfChartData.datasets = perfChartData.datasets.sort(
      (_a, b) => b.type ? 1 : -1
    );

    /**
     * woop woop! Hereby we instantiate the actual thing
     */
    return new Chart($('#performance-chart'), {
      type: 'bar',
      data: perfChartData,
      options: {
        elements: {
          line: {
            tension: 0
          }
        },
        scales: {
          yAxes: [{
            ticks: {
              min: lowerbound,
              max: upperbound
            }
          }]
        },
        plugins: {
          datalabels: {
            labels: {
              title: null
            }
          }
        }
      }
    });
  }
}

/**
 * @param {{ datasets: { data: { x: string }[] }[], labels: string[] }} distChartData
 * @param {boolean} aggregated
 */
function generateWeather(distChartData, aggregated) {
  console.log(distChartData);

  if (!distChartData || !distChartData.datasets) return;

  const datasets = aggregated
                 ? distChartData.datasets.filter(e => !e.type)
                 : distChartData.datasets;
  const labels = distChartData.labels;

  const weatherImages = {};
  for (const e of $('link[rel="preload"][data-weather]')) {
    weatherImages[e.dataset.weather] = e.href;
  }
  const participants = aggregated
                     ? datasets.map(e => e.label)
                     : labels;

  const distCountPerParticipant = labels.length;
  const participantsLength = participants.length;

  const averageDistances = aggregated ? datasets.map(
    e => e.data.reduce((a, b) => +a + +b, 0) / distCountPerParticipant
  ) : datasets[1].data.map(e => +e.x);

  console.log(datasets[0].data[0].x);

  const globalAverageDistance = +distChartData.datasets[0].data[0].x;


  function getWeather(distPercentage) {
    if (distPercentage < 3) return weatherImages['very-sunny'];
    if (distPercentage < 6) return weatherImages['sunny'];
    if (distPercentage < 10) return weatherImages['sunny-clouds'];
    if (distPercentage < 15) return weatherImages['cloudy'];
    if (distPercentage < 25) return weatherImages['rainy'];
    if (distPercentage < 35) return weatherImages['very-rainy'];
    if (distPercentage < 45) return weatherImages['stormy'];
    return weatherImages['very-stormy'];
  }

  /**
   * @type {{ global: string, individual: { participant: string, weather: string, value: string }[]}}
   */
  const _return = {
    global: getWeather(globalAverageDistance),
    individual: []
  };


  for (let i = 0; i < participantsLength; ++i) {
    const participant = participants[i];
    const averageDistance = averageDistances[i];

    _return.individual.push({
      participant,
      weather: getWeather(averageDistance),
      value: averageDistance.toFixed(1) + '%'
    });
  }

  let indivWeatherTableInner = '';
  for (const indiv of _return.individual) {
    indivWeatherTableInner += /*html*/`
      <tr>
        <td>${indiv.participant}</td>
        <td class="dist-weather-value">${indiv.value}</td>
        <td class="dist-weather-img" style="background-image: url(${indiv.weather})"></td>
      </tr>
    `;
  }

  _return.html = /*html*/`
    <div id="dist-weather">
      <div id="global-weather" class="dist-weather--item">
        <div class="global-weather--title"></div>
        <div class="global-weather--item">
          <div class="dist-weather-value">${globalAverageDistance.toFixed(1)}%</div>
          <div class="dist-weather-img" style="background-image: url(${_return.global})"></div>
        </div>
      </div>
      <div class="dist-weather--item">
        <table id="indiv-weather" class="striped">${indivWeatherTableInner}</table>
      </div>
    </div>
  `;

  return _return;
}
