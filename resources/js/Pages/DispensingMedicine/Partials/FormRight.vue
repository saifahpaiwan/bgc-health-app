<template>
    <div>
        <div class="row">
            <div class="col-md-2 form-group">
                <div class="btn-group w-100" style="margin-top: 27px;">
                    <button type="button" class="btn btn-secondary dropdown-toggle waves-effect w-100"
                        data-toggle="dropdown" aria-expanded="false">
                        <span id="text-bottom-dropdown">ค้นหาข้อมูลสินค้า</span> <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" x-placement="bottom-start">
                        <a class="dropdown-item btn-seleted-type" href="javascript:void(0);">ค้นหาข้อมูลสินค้า</a>
                        <a class="dropdown-item btn-seleted-type" href="javascript:void(0);">Scan Code</a>
                    </div>
                </div>
            </div>
            <div class="col-md-10 form-group" id="box-select-product">
                <label for="date"> ค้นหาข้อมูลผลิตภัณฑ์ยา <span class="text-danger">*</span> </label>
                <div class="input-group">
                    <select class="form-control" id="product_id " name="product_id ">
                        <option value=""> เลือกผลิตภัณฑ์ยา </option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary waves-effect waves-light" type="button">
                            <i class="fe-plus-circle"></i> เพิ่ม</button>
                    </div>
                </div>
            </div>
            <div class="col-md-10 form-group" id="box-scan-code" style="display: none;">
                <label for="date"> Scan Code สำหรับข้อมูลผลิตภัณฑ์ยา <span class="text-danger">*</span> </label>
                <input type="text" class="form-control" name="" id="input-scan-code"
                    placeholder="Scan Code | Ex. 00000000">
            </div>
            <div class="col-md-12 form-group">
                <div class="table-responsive" style="max-height: 255px; border: 1px solid #000;">

                    <table class="table table-hover mb-0 table-sm">
                        <thead class="thead-dark" style="position: sticky; top: 0; z-index: 99;">
                            <tr>
                                <th class="text-white">#ผลิตภัณฑ์ยา</th>
                                <th class="text-white" width="25%">จำนวน/หน่วย</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="vertical-align: middle;">
                                    <div class="d-flex">
                                        <img src="https://inwfile.com/s-cx/vwmjoh.jpg" alt="" width="50" height="50">
                                        <div class="ml-2">
                                            <div> <b>7600426</b> | <b class="text-danger">PAC/PC</b></div>
                                            <div>ผงน้ำตาลเกลือแร่ (Oral Rehydration Salt: ORS)</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="ระบุจำนวน" value="0">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary waves-effect waves-light"
                                                type="button">PC</button>
                                            <button class="btn btn-danger waves-effect waves-light"
                                                type="button">ลบ</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped></style>