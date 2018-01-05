$(document).ready(function () {
    var CashBack = {
        init: function () {
            this.SaveCredentials();
            this.FormValidation();
            this.TestData();
            this.Misc();
            this.SaveSettings();
            this.Registration();
            this.Sync();
            this.AddMerchant();
            this.Expender();
            this.SelectEnvoirment();
            this.Tabbular();
            this.FilterTransaction();
            this.TransDateFiltration();
            this.CBDateFiltration();
            this.CBFilterTransaction();
            this.__Editors();
            this.EmailTemplate();
            this.getTransactionColumnsByUser();
            this.SageFiltration();
        },
        __Editors: function () {
            tinymce.init({
                selector: '#template_editor',
                height: 250,
                menubar: false,
                plugins: [
                    "advlist autolink autosave link lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars nonbreaking",
                    "save table contextmenu directionality emoticons code textcolor paste textcolor colorpicker"
                ],
                toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
                toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking pagebreak restoredraft",
                menubar: false,
                toolbar_items_size: 'small',
                style_formats: [{
                        title: 'Bold text',
                        inline: 'b'
                    }, {
                        title: 'Red text',
                        inline: 'span',
                        styles: {
                            color: '#ff0000'
                        }
                    }, {
                        title: 'Red header',
                        block: 'h1',
                        styles: {
                            color: '#ff0000'
                        }
                    }, {
                        title: 'Example 1',
                        inline: 'span',
                        classes: 'example1'
                    }, {
                        title: 'Example 2',
                        inline: 'span',
                        classes: 'example2'
                    }, {
                        title: 'Table styles'
                    }, {
                        title: 'Table row 1',
                        selector: 'tr',
                        classes: 'tablerow1'
                    }],
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css'
                ]
            });
        },
        FormValidation: function () {
            $("#savesetting").validationEngine();
            $("#loginForm").validationEngine();
            $("#saveadminsetting").validationEngine();
            $("#formRegister").validationEngine();
            $("#add_merchants_form").validationEngine();
            $("#from").datepicker();
            $("#to").datepicker();
            $("#froms").datepicker();
            $("#tos").datepicker();
            $("#fromx").datepicker();
            $("#tox").datepicker();
        },
        SaveCredentials: function () {
            $("#saveCredentials").on('click', function (e) {
                e.preventDefault();
                var $that = $(this);
                if ($("#savesetting").validationEngine('validate')) {
                    var dataSet = $(this).parents("form").serialize();
                    $.ajax({
                        type: 'POST',
                        url: saveCredentialsURL,
                        dataType: 'json',
                        data: dataSet,
                        beforeSend: function () {
                            $that.css({'opacity': 0.5});
                            $that.attr('disabled', true);
                        },
                        success: function (res) {
                            if (res) {
                                $("input[name='auth']").val(res.authorization);
                                $(".displayMessage").find('p').text(res.message);
                                $(".displayMessage").fadeIn();
                            }
                            $that.css({'opacity': 1});
                            $that.attr('disabled', false);
                        },
                        complete: function () {
                            setTimeout(function () {
                                $(".displayMessage").fadeOut();
                            }, 2500);
                        }
                    });
                }
            });
        },
        TestData: function () {
            $("#testCredentials").on('click', function (e) {
                var $that = $(this);
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: TestData,
                    beforeSend: function () {
                        $that.css({'opacity': 0.5});
                        $that.attr('disabled', true);
                    },
                    success: function (response, status, xhr) {
                        $(".showdata").fadeIn();
                        $(".showdata").find(".showdata").fadeIn();
                        $(".showdata").find("pre").html(response);
                        $that.css({'opacity': 1});
                        $that.attr('disabled', false);
                    }
                });
            });
        },
        Misc: function () {
            if ($.fn.DataTable) {
                $('#example2').DataTable({
                    responsive: true
                });
                $('#example3').DataTable({
                    responsive: true
                });
            }
            if ($.fn.ace_scroll) {
                $('.showdata').ace_scroll({
                    size: 300
                });
            }
            $(window).load(function () {
                $(".loading").hide();
            });
        },
        SaveSettings: function () {
            $("#SaveAdminSettings").on('click', function (e) {
                e.preventDefault();
                if ($("#savesetting").validationEngine('validate')) {
                    var dataSet = $(this).parents("form").serialize();
                    $.ajax({
                        type: 'POST',
                        url: saveSettingURL,
                        dataType: 'json',
                        data: dataSet,
                        success: function (res) {
                            if (res) {
                                $("input[name='auth']").val(res.authorization);
                                $(".displayMessage").find('p').text(res.message);
                                $(".displayMessage").fadeIn();
                            }
                        },
                        complete: function () {
                            setTimeout(function () {
                                $(".displayMessage").fadeOut();
                            }, 2500);
                        }
                    });
                }
            });
        },
        Registration: function () {
            $("#registerform_btn").on('click', function (e) {
                e.preventDefault();
                var dataSerial = $("#formRegister").serialize();
                var url = $("#formRegister").attr('action');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: dataSerial,
                    dataType: 'json',
                    success: function (res) {
                        $(".showmess").html(res.mess);
                    }
                });
            });
        },
        Sync: function () {
            $(".sync").on('click', function (e) {
                $(".main-content-inner").css({
                    'opacity': '0.5',
                    'z-index': '-1',
                    'position': 'relative',
                });
                $(".preloader").fadeIn();
            });
        },
        AddMerchant: function () {
            $("select[name='endpoint']").on('change', function (e) {
                var check = $(this).find("option:selected").attr('data-type');
                $("#merchant_type").val(check);
                if (check == "nmi") {
                    $(".authorize_field").hide();
                    $(".sage_field").hide();
                    $(".nmi_field").show();
                }
                if (check == "authorize") {
                    $(".authorize_field").show();
                    $(".sage_field").hide();
                    $(".nmi_field").hide();
                }
                if (check == "sage") {
                    $(".authorize_field").hide();
                    $(".sage_field").show();
                    $(".nmi_field").hide();
                }
            });
        },
        Expender: function () {
            $(".colheading a").on('click', function (e) {
                e.preventDefault();
                $(this).parents(".makecenter").next().slideToggle();
            });
        },
        SelectEnvoirment: function () {
            $(".credentials").on('click', function (e) {
                var newUrl = $(this).attr('data-endpoint');
                console.log($("select[name='endpoint']").find("option[data-type='authorize']").val(newUrl));
            });
        },
        Tabbular: function () {
            $("#tabbs li a").on('click', function (e) {
                e.preventDefault();
                var hash = $(this).attr('href');
                $("#tabbs li a").removeClass('selected');
                $(".tabs").fadeOut(0);
                $(this).addClass('selected');
                $(hash).fadeIn();
            });
        },
        FilterTransaction: function () {
            if ($.fn.DataTable) {
                var table = $('#example');
                table.dataTable({
                    "order": [[4, "desc"]]
                });
                var $jqDate = jQuery('#card_no');
                $jqDate.bind('keyup', 'keydown', function (e) {
                    //To accomdate for backspacing, we detect which key was pressed - if backspace, do nothing:
                    if (e.which !== 8) {
                        var numChars = $jqDate.val().length;
                        if (numChars === 6 || numChars === 6) {
                            var thisVal = $jqDate.val();
                            thisVal += 'xxxxxx';
                            $jqDate.val(thisVal);
                        }
                    }
                });
                $("#filter").on('click', function (e) {
                    e.preventDefault();
                    var trans_id = $("#trans_id").val();
                    var from = $('#from').val();
                    var to = $('#to').val();
                    var card_num = $("#card_no").val();
                    var merch_type = $("#merch_type").val();
                    var processor = $("#processor").val();
                    var status_matched = $("#status_matched").val();
                    if (trans_id !== "")
                        table.api().columns(0).search(trans_id).draw();
                    if (processor !== "")
                        table.api().columns(3).search(processor).draw();
                    if (merch_type !== "")
                        table.api().columns(4).search(merch_type).draw();
                    if (card_num !== "")
                        table.api().columns(6).search(card_num).draw();
                    if (status_matched !== "")
                        table.api().columns(7).search(status_matched).draw();
                    if (!from && !trans_id && !card_num && !processor && !merch_type && !status_matched) {
                        table.api().columns(0).search("").draw();
                        table.api().columns(1).search("").draw();
                        table.api().columns(2).search("").draw();
                        table.api().columns(3).search("").draw();
                        table.api().columns(4).search("").draw();
                        table.api().columns(5).search("").draw();
                        table.api().columns(6).search("").draw();
                        table.api().columns(7).search("").draw();
                        table.api().columns(8).search("").draw();
                    }
                    if (table.api().page.info().recordsDisplay < 1) {
                        table.api().columns(0).search("").draw();
                        table.api().columns(1).search("").draw();
                        table.api().columns(2).search("").draw();
                        table.api().columns(3).search("").draw();
                        table.api().columns(4).search("").draw();
                        table.api().columns(5).search("").draw();
                        table.api().columns(6).search("").draw();
                        table.api().columns(7).search("").draw();
                        table.api().columns(8).search("").draw();
                        setTimeout(function () {
                            if (trans_id !== "") {
                                table.api().columns(0).search(trans_id).draw();
                            }
                            if (processor !== "") {
                                table.api().columns(3).search(processor).draw();
                            }
                            if (merch_type !== "") {
                                table.api().columns(4).search(merch_type).draw();
                            }
                            if (card_num !== "") {
                                table.api().columns(6).search(card_num).draw();
                            }
                            if (status_matched !== "") {
                                table.api().columns(7).search(status_matched).draw();
                            }
                        }, 20);
                    }
                });
                $("#resetForm").on('click', function (e) {
                    e.preventDefault();
                    table.api().columns(0).search("").draw();
                    table.api().columns(1).search("").draw();
                    table.api().columns(2).search("").draw();
                    table.api().columns(3).search("").draw();
                    table.api().columns(4).search("").draw();
                    table.api().columns(5).search("").draw();
                    table.api().columns(6).search("").draw();
                    table.api().columns(7).search("").draw();
                    table.api().columns(8).search("").draw();
                    $("#transactionDate").val('');
                    $("#trans_id").val('');
                    $("#card_no").val('');
                    $("#merch_type").val('');
                    $("#from").val('');
                    $("#to").val('');
                    $("#status_matched").val('');
                    $("#processor").val('');
                });
                $("#filterz").on('click', function (e) {
                    e.preventDefault();
                    var trans_id = $("#trans_id").val();
                    var from = $('#from').val();
                    var to = $('#to').val();
                    var card_num = $("#card_no").val();
                    var merch_type = $("#merch_type").val();
                    var processor = $("#processor").val();
                    var users = $("#users").val();
                    var status_matched = $("#status_matched").val();
                    var status_alert = $("#status_alert").val();
                    if (users !== "") {
                        if (trans_id !== "")
                            table.api().columns(0).search(trans_id).draw();
                        else
                            table.api().columns(0).search("").draw();
                        if (users !== "")
                            table.api().columns(9).search(users).draw();
                        else
                            table.api().columns(9).search("").draw();
                        if (merch_type !== "")
                            table.api().columns(4).search(merch_type).draw();
                        else
                            table.api().columns(4).search("").draw();
                        if (processor !== "")
                            table.api().columns(3).search(processor).draw();
                        else
                            table.api().columns(3).search("").draw();
                        if (card_num !== "")
                            table.api().columns(7).search(card_num).draw();
                        else
                            table.api().columns(7).search("").draw();
                        if (status_matched !== "")
                            table.api().columns(8).search(status_matched).draw();
                        else
                            table.api().columns(8).search("").draw();
                        if (status_alert !== "")
                            table.api().columns(10).search(status_alert).draw();
                        else
                            table.api().columns(10).search("").draw();

                        if (!from && !trans_id && !card_num && !processor && !merch_type && !status_matched && !status_alert) {
                            table.api().columns(0).search("").draw();
                            table.api().columns(1).search("").draw();
                            table.api().columns(2).search("").draw();
                            table.api().columns(3).search("").draw();
                            table.api().columns(4).search("").draw();
                            table.api().columns(5).search("").draw();
                            table.api().columns(6).search("").draw();
                            table.api().columns(7).search("").draw();
                            table.api().columns(8).search("").draw();
                            table.api().columns(9).search("").draw();
                            table.api().columns(10).search("").draw();
                        }
                        if (table.api().page.info().recordsDisplay < 1) {
                            table.api().columns(0).search("").draw();
                            table.api().columns(1).search("").draw();
                            table.api().columns(2).search("").draw();
                            table.api().columns(3).search("").draw();
                            table.api().columns(4).search("").draw();
                            table.api().columns(5).search("").draw();
                            table.api().columns(6).search("").draw();
                            table.api().columns(7).search("").draw();
                            table.api().columns(8).search("").draw();
                            table.api().columns(9).search("").draw();
                            table.api().columns(10).search("").draw();
                            setTimeout(function () {
                                if (trans_id !== "")
                                    table.api().columns(0).search(trans_id).draw();
                                else
                                    table.api().columns(0).search("").draw();
                                if (users !== "")
                                    table.api().columns(9).search(users).draw();
                                else
                                    table.api().columns(9).search("").draw();
                                if (merch_type !== "")
                                    table.api().columns(4).search(merch_type).draw();
                                else
                                    table.api().columns(4).search("").draw();
                                if (processor !== "")
                                    table.api().columns(3).search(processor).draw();
                                else
                                    table.api().columns(3).search("").draw();
                                if (card_num !== "")
                                    table.api().columns(6).search(card_num).draw();
                                else
                                    table.api().columns(6).search("").draw();
                                if (status_matched !== "")
                                    table.api().columns(8).search(status_matched).draw();
                                else
                                    table.api().columns(8).search("").draw();

                                if (status_alert !== "")
                                    table.api().columns(10).search(status_alert).draw();
                                else
                                    table.api().columns(10).search("").draw();
                            }, 20);
                        }
                    } else {
                        alert("please select user");
                    }
                });
                $("#resetFormz").on('click', function (e) {
                    e.preventDefault();
                    table.api().columns(0).search("").draw();
                    table.api().columns(1).search("").draw();
                    table.api().columns(2).search("").draw();
                    table.api().columns(3).search("").draw();
                    table.api().columns(4).search("").draw();
                    table.api().columns(5).search("").draw();
                    table.api().columns(6).search("").draw();
                    table.api().columns(7).search("").draw();
                    table.api().columns(8).search("").draw();
                    table.api().columns(9).search("").draw();
                    table.api().columns(10).search("").draw();
                    $("#transactionDate").val('');
                    $("#trans_id").val('');
                    $("#card_no").val('');
                    $("#merch_type").val('');
                    $("#from").val('');
                    $("#to").val('');
                    $("#status_matched").val('');
                    $("#processor").val('');
                    $("#users").val('');
                    $("#status_alert").val('');
                });
            }
        }
        ,
        TransDateFiltration: function () {
            $(document).on('change', '#from, #to', function () {
                $('#example').dataTable().DataTable().draw();
            });
            $.fn.dataTableExt.afnFiltering.push(
                    function (oSettings, aData, iDataIndex) {
                        if (($('#from').length > 0 && $('#from').val() !== '') || ($('#to').length > 0 && $('#to').val() !== '')) {
                            var today = new Date();
                            var dd = today.getDate();
                            var mm = today.getMonth();
                            var yyyy = today.getFullYear();
                            console.log($('#from').val() + "\/" + $('#to').val());
                            if (dd < 10)
                                dd = '0' + dd;
                            if (mm < 10)
                                mm = '0' + mm;
                            today = mm + '/' + dd + '/' + yyyy;
                            var minVal = $('#from').val();
                            var maxVal = $('#to').val();
                            //alert(minVal+"   ----   "+maxVal);
                            if (minVal !== '' || maxVal !== '') {
                                var iMin_temp = minVal;
                                if (iMin_temp === '') {
                                    iMin_temp = '01/01/1980';
                                }

                                var iMax_temp = maxVal;
                                var arr_min = iMin_temp.split("/");
                                var arr_date = aData[6].split("/");
//console.log(arr_min[2]+"-- "+arr_min[0]+" --"+arr_min[1]);
                                var iMin = new Date(arr_min[2], arr_min[0] - 1, arr_min[1]);
                                //  console.log(iMin);
                                // console.log(" --"+yyy);
                                var iMax = '';
                                if (iMax_temp != '') {
                                    var arr_max = iMax_temp.split("/");
                                    iMax = new Date(arr_max[2], arr_max[0] - 1, arr_max[1], 0, 0, 0, 0);
                                }
                                var iDate = new Date(arr_date[2], arr_date[0] - 1, arr_date[1], 0, 0, 0, 0);
                                //alert(iMin+" -- "+iMax);
                                //  console.log("Test data "+iMin+" -- "+iMax+"-- "+iDate+" --"+(iMin <= iDate && iDate <= iMax));
                                if (iMin === "" && iMax === "") {
                                    return true;
                                } else if (iMin === "" && iDate < iMax) {
//                                    alert("inside max values" + iDate);
                                    return true;
                                } else if (iMax === "" && iDate >= iMin) {
//                                    alert("inside max value is null" + iDate);
                                    return true;
                                } else if (iMin <= iDate && iDate <= iMax) {
//                                    alert("inside both values" + iDate);
                                    return true;
                                }
                                return false;
                            }
                        }
                        return true;
                    });
        }
        ,
        CBDateFiltration: function () {
            $(document).on('change', '#froms, #tos', function () {
                $('#example').dataTable().DataTable().draw();
            });
            $.fn.dataTableExt.afnFiltering.push(
                    function (oSettings, aData, iDataIndex) {
                        if (($('#froms').length > 0 && $('#froms').val() !== '') || ($('#tos').length > 0 && $('#tos').val() !== '')) {
                            var today = new Date();
                            var dd = today.getDate();
                            var mm = today.getMonth();
                            var yyyy = today.getFullYear();
                            console.log($('#froms').val() + "\/" + $('#tos').val());
                            if (dd < 10)
                                dd = '0' + dd;
                            if (mm < 10)
                                mm = '0' + mm;
                            today = mm + '/' + dd + '/' + yyyy;
                            var minVal = $('#froms').val();
                            var maxVal = $('#tos').val();
                            //alert(minVal+"   ----   "+maxVal);
                            if (minVal !== '' || maxVal !== '') {
                                var iMin_temp = minVal;
                                if (iMin_temp === '') {
                                    iMin_temp = '01/01/1980';
                                }

                                var iMax_temp = maxVal;
                                var arr_min = iMin_temp.split("/");
                                var arr_date = aData[6].split("/");
                                var iMin = new Date(arr_min[2], arr_min[0] - 1, arr_min[1]);
                                var iMax = '';
                                if (iMax_temp != '') {
                                    var arr_max = iMax_temp.split("/");
                                    iMax = new Date(arr_max[2], arr_max[0] - 1, arr_max[1], 0, 0, 0, 0);
                                }
                                var iDate = new Date(arr_date[2], arr_date[0] - 1, arr_date[1], 0, 0, 0, 0);
                                if (iMin === "" && iMax === "") {
                                    return true;
                                } else if (iMin === "" && iDate < iMax) {
                                    return true;
                                } else if (iMax === "" && iDate >= iMin) {
                                    return true;
                                } else if (iMin <= iDate && iDate <= iMax) {
                                    return true;
                                }
                                return false;
                            }
                        }
                        return true;
                    });
        }
        ,
        CBFilterTransaction: function () {
            if ($.fn.DataTable) {
                var table = $('#example');
                table.dataTable();
                var $jqDate = jQuery('#card_nos');
                $jqDate.bind('keyup', 'keydown', function (e) {
                    //To accomdate for backspacing, we detect which key was pressed - if backspace, do nothing:
                    if (e.which !== 8) {
                        var numChars = $jqDate.val().length;
                        if (numChars === 6 || numChars === 6) {
                            var thisVal = $jqDate.val();
                            thisVal += 'xxxxxx';
                            $jqDate.val(thisVal);
                        }
                    }
                });
                $("#filters").on('click', function (e) {
                    e.preventDefault();
                    var case_id = $("#case_id").val();
                    var froms = $('#froms').val();
                    var merch_processor = $('#merch_processor').val();
                    var merch_no = $('#merch_no').val();
                    var tos = $('#tos').val();
                    var card_nos = $("#card_nos").val();
                    var mer_name = $("#mer_name").val();
                    if (merch_no !== "")
                        table.api().columns(0).search(merch_no).draw();
                    if (merch_processor !== "")
                        table.api().columns(2).search(merch_processor).draw();
                    if (case_id !== "")
                        table.api().columns(5).search(case_id).draw();
                    if (card_nos !== "")
                        table.api().columns(7).search(card_nos).draw();
                    if (mer_name !== "")
                        table.api().columns(4).search(mer_name).draw();
                    if (!froms && !tos && !merch_no && !merch_processor && !case_id && !card_nos && !mer_name) {
                        table.api().columns(0).search("").draw();
                        table.api().columns(1).search("").draw();
                        table.api().columns(2).search("").draw();
                        table.api().columns(3).search("").draw();
                        table.api().columns(4).search("").draw();
                        table.api().columns(5).search("").draw();
                        table.api().columns(6).search("").draw();
                        table.api().columns(7).search("").draw();
                        table.api().columns(8).search("").draw();
                    }


                    if (table.api().page.info().recordsDisplay < 1) {
                        table.api().columns(0).search("").draw();
                        table.api().columns(1).search("").draw();
                        table.api().columns(2).search("").draw();
                        table.api().columns(3).search("").draw();
                        table.api().columns(4).search("").draw();
                        table.api().columns(5).search("").draw();
                        table.api().columns(6).search("").draw();
                        table.api().columns(7).search("").draw();
                        table.api().columns(8).search("").draw();
                        setTimeout(function () {
                            if (merch_no !== "")
                                table.api().columns(0).search(merch_no).draw();
                            if (merch_processor !== "")
                                table.api().columns(2).search(merch_processor).draw();
                            if (case_id !== "")
                                table.api().columns(5).search(case_id).draw();
                            if (card_nos !== "")
                                table.api().columns(7).search(card_nos).draw();
                            if (mer_name !== "")
                                table.api().columns(4).search(mer_name).draw();
                        }, 20);
                    }
                });
                $("#resetForms").on('click', function (e) {
                    e.preventDefault();
                    table.api().columns(0).search("").draw();
                    table.api().columns(1).search("").draw();
                    table.api().columns(2).search("").draw();
                    table.api().columns(3).search("").draw();
                    table.api().columns(4).search("").draw();
                    table.api().columns(5).search("").draw();
                    table.api().columns(6).search("").draw();
                    table.api().columns(7).search("").draw();
                    table.api().columns(8).search("").draw();
                    $("#case_id").val('');
                    $("#card_nos").val('');
                    $("#mer_name").val('');
                    $("#froms").val('');
                    $("#tos").val('');
                    $("#merch_processor").val('');
                    $("#merch_no").val('');
                });
            }
        }
        ,
        EmailTemplate: function () {
            $("#saveTemplate").on('click', function (e) {
                e.preventDefault();
                var content = tinymce.activeEditor.getContent({format: 'html'});
                $.ajax({
                    type: 'POST',
                    url: save_template,
                    data: {
                        content: content
                    },
                    success: function (res) {
                        var response = JSON.parse(res);
                        if (response) {
                            $(".messageShow").html(response.response);
                        }
                    }
                });
            });
        }
        ,
        getTransactionColumnsByUser: function () {
            $("#users").on('change', function (e) {
                var id = $(this).find("option:selected").attr('data-id');
                CashBack.doAjax(getUserMerType, id);
                CashBack.doAjax(getUserProcess, id);
            });
        }
        ,
        doAjax: function (url, id, action) {
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {
                    user_id: id,
                    action: action
                },
                beforeSend: function () {
                    $("#merch_type").html("");
                    $("#processor").html("");
                },
                success: function (res) {
                    if (typeof res.merchant_type != 'undefined') {
                        var elemn_mer = '<option value="">Select Merchant Gateway</option>';
                        for (var i = 0; i < res.merchant_type.records.length; i++) {
                            elemn_mer += '<option value="' + res.merchant_type.records[i].mer_type + '">' + res.merchant_type.records[i].mer_type + '</option>';
                        }
                        $("#merch_type").html(elemn_mer);
                        $("#merch_type").find("option:eq(2)").attr('selected', 'selected');
                    }
                    if (typeof res.processor != 'undefined') {
                        var elemn_pro = '<option value="">Select Processor</option>';
                        for (var i = 0; i < res.processor.records.length; i++) {
                            elemn_pro += '<option value="' + res.processor.records[i].processor_id + '">' + res.processor.records[i].processor_id + '</option>';
                        }
                        $("#processor").html(elemn_pro);
                        $("#processor").find("option:eq(1)").attr('selected', 'selected');
                    }
                }
            });
        }
        ,
        SageFiltration: function () {

            if ($.fn.DataTable) {
                var table = $('#example');
                table.dataTable();
                var $jqDate = jQuery('#card_no');
                $jqDate.bind('keyup', 'keydown', function (e) {
                    //To accomdate for backspacing, we detect which key was pressed - if backspace, do nothing:
                    if (e.which !== 8) {
                        var numChars = $jqDate.val().length;
                        if (numChars === 6 || numChars === 6) {
                            var thisVal = $jqDate.val();
                            thisVal += 'xxxxxx';
                            $jqDate.val(thisVal);
                        }
                    }
                });
                $(document).on('change', '#fromx, #tox', function () {
                    $('#example').dataTable().DataTable().draw();
                });
                $.fn.dataTableExt.afnFiltering.push(
                        function (oSettings, aData, iDataIndex) {
                            if (($('#fromx').length > 0 && $('#fromx').val() !== '') || ($('#tox').length > 0 && $('#tox').val() !== '')) {
                                var today = new Date();
                                var dd = today.getDate();
                                var mm = today.getMonth();
                                var yyyy = today.getFullYear();
                                console.log($('#fromx').val() + "\/" + $('#tox').val());
                                if (dd < 10)
                                    dd = '0' + dd;
                                if (mm < 10)
                                    mm = '0' + mm;
                                today = mm + '/' + dd + '/' + yyyy;
                                var minVal = $('#fromx').val();
                                var maxVal = $('#tox').val();
                                //alert(minVal+"   ----   "+maxVal);
                                if (minVal !== '' || maxVal !== '') {
                                    var iMin_temp = minVal;
                                    if (iMin_temp === '') {
                                        iMin_temp = '01/01/1980';
                                    }
                                    var iMax_temp = maxVal;
                                    var arr_min = iMin_temp.split("/");
                                    var arr_date = aData[10].split("/");
                                    var iMin = new Date(arr_min[2], arr_min[0] - 1, arr_min[1]);
                                    var iMax = '';
                                    if (iMax_temp != '') {
                                        var arr_max = iMax_temp.split("/");
                                        iMax = new Date(arr_max[2], arr_max[0] - 1, arr_max[1], 0, 0, 0, 0);
                                    }
                                    var iDate = new Date(arr_date[2], arr_date[0] - 1, arr_date[1], 0, 0, 0, 0);
                                    if (iMin === "" && iMax === "") {
                                        return true;
                                    } else if (iMin === "" && iDate < iMax) {
                                        return true;
                                    } else if (iMax === "" && iDate >= iMin) {
                                        return true;
                                    } else if (iMin <= iDate && iDate <= iMax) {
                                        return true;
                                    }
                                    return false;
                                }
                            }
                            return true;
                        });
                $("#filterv").on('click', function (e) {
                    e.preventDefault();
                    var mer_namex = $("#mer_namex").val();
                    var location = $('#location').val();
                    var ter_id = $('#ter_id').val();
                    var fromx = $("#fromx").val();
                    var tox = $("#tox").val();
                    var rout_no = $("#rout_no").val();

                    if (mer_namex !== "")
                        table.api().columns(12).search(mer_namex).draw();
                    else
                        table.api().columns(12).search("").draw();

                    if (location !== "")
                        table.api().columns(2).search(location).draw();
                    else
                        table.api().columns(2).search("").draw();

                    if (ter_id !== "")
                        table.api().columns(3).search(ter_id).draw();
                    else
                        table.api().columns(3).search("").draw();

                    if (rout_no !== "")
                        table.api().columns(3).search(rout_no).draw();
                    else
                        table.api().columns(3).search("").draw();

                    if (!mer_namex && !location && !ter_id && !fromx && !tox && !rout_no) {
                        table.api().columns(0).search("").draw();
                        table.api().columns(1).search("").draw();
                        table.api().columns(2).search("").draw();
                        table.api().columns(3).search("").draw();
                        table.api().columns(4).search("").draw();
                        table.api().columns(5).search("").draw();
                        table.api().columns(6).search("").draw();
                        table.api().columns(7).search("").draw();
                        table.api().columns(8).search("").draw();
                        table.api().columns(9).search("").draw();
                        table.api().columns(10).search("").draw();
                        table.api().columns(11).search("").draw();
                        table.api().columns(12).search("").draw();
                        table.api().columns(13).search("").draw();
                        table.api().columns(14).search("").draw();
                    }
                    if (table.api().page.info().recordsDisplay < 1) {
                        table.api().columns(0).search("").draw();
                        table.api().columns(1).search("").draw();
                        table.api().columns(2).search("").draw();
                        table.api().columns(3).search("").draw();
                        table.api().columns(4).search("").draw();
                        table.api().columns(5).search("").draw();
                        table.api().columns(6).search("").draw();
                        table.api().columns(7).search("").draw();
                        table.api().columns(8).search("").draw();
                        table.api().columns(9).search("").draw();
                        table.api().columns(10).search("").draw();
                        table.api().columns(11).search("").draw();
                        table.api().columns(12).search("").draw();
                        table.api().columns(13).search("").draw();
                        table.api().columns(14).search("").draw();
                        setTimeout(function () {
                            if (mer_namex !== "")
                                table.api().columns(12).search(mer_namex).draw();
                            else
                                table.api().columns(12).search("").draw();

                            if (location !== "")
                                table.api().columns(2).search(location).draw();
                            else
                                table.api().columns(2).search("").draw();

                            if (ter_id !== "")
                                table.api().columns(3).search(ter_id).draw();
                            else
                                table.api().columns(3).search("").draw();

                            if (rout_no !== "")
                                table.api().columns(7).search(rout_no).draw();
                            else
                                table.api().columns(7).search("").draw();
                        }, 20);
                    }
                });
                $("#resetFormv").on('click', function (e) {
                    e.preventDefault();
                    table.api().columns(0).search("").draw();
                    table.api().columns(1).search("").draw();
                    table.api().columns(2).search("").draw();
                    table.api().columns(3).search("").draw();
                    table.api().columns(4).search("").draw();
                    table.api().columns(5).search("").draw();
                    table.api().columns(6).search("").draw();
                    table.api().columns(7).search("").draw();
                    table.api().columns(8).search("").draw();
                    table.api().columns(9).search("").draw();
                    table.api().columns(10).search("").draw();
                    table.api().columns(11).search("").draw();
                    table.api().columns(12).search("").draw();
                    table.api().columns(13).search("").draw();
                    table.api().columns(14).search("").draw();
                    $("#mer_namex").val('');
                    $("#location").val('');
                    $("#ter_id").val('');
                    $("#fromx").val('');
                    $("#tox").val('');
                    $("#to").val('');
                    $("#rout_no").val('');
                });
            }
        }
    }
    ;
    CashBack.init();
});


