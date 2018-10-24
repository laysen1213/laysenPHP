<template>
    <div class="el-form-item__content">
        <div class="avatar-uploader">
            <div tabindex="0" class="el-upload el-upload--text" @click="upload_click">
                <i class="el-icon-plus avatar-uploader-icon"></i>
                <input type="file" name="file" class="el-upload__input" ref="files" @change="upload_file($event)">
                <img :src="imgSrc" v-show="imgShow" class="imgSrc">
            </div>
        </div>
    </div>
</template>
<style scoped>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 178px;
    height: 178px;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader .el-upload:hover .avatar-uploader-icon{
      z-index: 150;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -14px;
    margin-left: -14px;
    text-align: center;
    z-index: 50;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
  .imgSrc{position: absolute;top: 0;left: 0;width: 100%;z-index: 100;}
</style>    
<script type="text/ecmascript-6">
export default {
    name: 'upload',
    props: {
        imgSize : {
            type : Number,
            default : 1,
            
        },
        image: {
            type : String,
            default: ''
        }
    },
    data() {
        return {
            imgData: {  
                accept: 'image/gif, image/jpeg, image/png, image/jpg',  
            },
            imgShow:false,
            imgSrc : ''
        }
    },
    created () {
        // this.img_upload();
    },
    watch : {
        image () {
            this.img_upload();
        }
    },
    methods: {
        img_upload () {
            this.imgSrc = this.image;
            this.imgShow = this.imgSrc == '' ? false : true;
        },
        upload_click (e) {
            this.$refs.files.click();
        },
        upload_file(event){
            var reader = new FileReader();  
            var img=  event.target.files[0];  
            if(!img){ return false;}
            var type = img.type;//文件的类型，判断是否是图片  
            var size = img.size;//文件的大小，判断图片的大小  
            if(this.imgData.accept.indexOf(type) == -1){  
                this.$message({
                    showClose: true,
                    type: 'error',
                    message: '请选择正确的图片格式！'
                });
                return false;  
            }  
            if(size > 1024 * 1024 * this.imgSize){  
                this.$message({
                    showClose: true,
                    type: 'error',
                    message: '请选择'+this.imgSize+'M以内的图片！'
                });
                return false;  
            }  
            reader.readAsDataURL(img);  
            reader.onload = e => {
                this.imgShow = true;
                this.imgSrc = String(e.target.result);
                
            }
        }
    }
}
</script>