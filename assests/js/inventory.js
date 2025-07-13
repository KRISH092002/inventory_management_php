var app = angular.module("myApp", []);


app.controller("inventoryCntrl", function ($scope, $http) {
    $scope.getUserData = () => {


        $http.get("./userdata.php")
            .then(function (res) {
                if (res.status == 200) {
                    $scope.userData = res.data
                } else {
                    alert('something went wrong');
                }
            });

    }
    $scope.getTableData = () => {


        $http.get("./get_inventory_list.php")
            .then(function (res) {
                if (res.status == 200) {
                    $scope.tableData = res.data
                } else {
                    alert('something went wrong');
                }
            });

    }
    $scope.getAccessories = (data, e) => {
        if (data != null) {
            let accArr = data.split(','), a = '';
            for (let i = 0; i < accArr.length; i++) {
                a += `<li>${accArr[i]}</li>`;
            }
            $(`.${e}`).ready(() => {
                $(`.${e}`).html(a)
            })
        }
    }

    $scope.add = (a, b, elem, index) => {
       let newval = $(elem.currentTarget).prev().val()
        $.ajax({
            url: "./add.php",
            type: "POST",
            data: {
                id: a,
                name: $scope.userData.name,
                option: newval
            },
            success: function (data) {
                if (data == "You can't unblock without blocking" || data == "Error in time update") {
                    alert(data);
                    $(elem.currentTarget).prev().val($scope.tableData[index].Status)
                } else if (data && (data.startsWith('{') && data.endsWith('}')) && JSON.parse(data)) {
                    data = JSON.parse(data)
                    if (data.report && data.report == 'wrongblocked') {
                        alert(data.msg);
                        $(elem.currentTarget).prev().val($scope.tableData[index].Status)
                    }
                }
                else if (data != '') {
                    $scope.tableData[index].Status = newval
                    alert(data);
                    location.reload()
                }

            },
            error: (err) => {
                alert(err)
                $(elem.currentTarget).prev().val($scope.tableData[index].Status)
            }

        });
    }

})