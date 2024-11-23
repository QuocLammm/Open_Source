var ctx = document.getElementById("myChart").getContext("2d");
var earning = document.getElementById("earning").getContext("2d");

// Cập nhật biểu đồ myChart thành biểu đồ doanh thu theo ngày
var myChart = new Chart(ctx, {
  type: "line", // Loại biểu đồ đường
  data: {
    labels: ["01 Nov", "02 Nov", "03 Nov", "04 Nov", "05 Nov", "06 Nov", "07 Nov"], // Các ngày
    datasets: [
      {
        label: "Daily Revenue",
        data: [500, 700, 450, 800, 650, 900, 750], // Doanh thu theo ngày
        borderColor: "rgba(75, 192, 192, 1)", // Màu đường
        backgroundColor: "rgba(75, 192, 192, 0.2)", // Màu nền
        borderWidth: 2, // Độ rộng đường
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      x: {
        title: {
          display: true,
          text: "Date", // Tiêu đề trục x
        },
      },
      y: {
        title: {
          display: true,
          text: "Revenue (in USD)", // Tiêu đề trục y
        },
        beginAtZero: true, // Bắt đầu từ 0
      },
    },
  },
});

var myChart = new Chart(earning, {
  type: "bar",
  data: {
    labels: [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ],
    datasets: [
      {
        label: "Earning",
        data: [
          4500, 4106, 7005, 6754, 5154, 4554, 7815, 3152, 12204, 4457, 8740,
          11000,
        ],
        backgroundColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)",
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(255, 159, 64, 1)",
        ],
      },
    ],
  },
  options: {
    responsive: true,
  },
});


