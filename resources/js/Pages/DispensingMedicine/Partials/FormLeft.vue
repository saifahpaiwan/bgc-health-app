<template>
    <div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="empolyee_code"> กรอกข้อมูลรหัสพนักงาน <span class="text-danger">*</span> </label>
                <input type="empolyee_code" class="form-control"
                    name="empolyee_code" id="empolyee_code" placeholder="Empolyee Code." value=""> 
            </div>
            <div class="col-md-12 form-group">
                <div class="p-2" style="background: #eafff1; border: 1px solid #36b65e; border-radius: 5px;">
                    <label for="description"> ข้อมูลพนักงาน </label>
                    <ul>
                        <li>Plant : BG</li>
                        <li>รหัสพนักงาน : 66000448</li>
                        <li>ชื่อ-นามสกุล : สายฟ้า ไพรวรรณ์</li>
                        <li>อีเมล : saifah.p@bgiglass.com</li>
                    </ul>
                    <i class="fe-check-circle h2 m-0"
                        style="position: absolute; top: 10px; right: 1.5rem; color: #36b65e;"></i>
                </div>
            </div>
            <div class="col-md-12 form-group">
                <label for="description"> รายละเอียดเพิ่มเติม </label>
                <textarea class="form-control" rows="3" name="description" id="description" placeholder="Description."> </textarea> 
            </div>
            <div class="col-md-12 form-group">
                <button class="btn btn-lg btn-primary waves-effect w-100 waves-light" type="button">
                    <i class="fe-save"></i> 
                    ยืนยันการจ่ายยา 
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

</script>

<style scoped></style>