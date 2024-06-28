import os
import shutil

build_dir = "../frontend"
dist_dir = "../frontend/dist"
backend_dir = "../backend"
final_dir = "build"
os.chdir(build_dir)
print("now path:")
print("==========")
print(os.getcwd())
is_install = os.system("npm install")
if(is_install == 0):
    print("插件安装成功，正在进行安装")
is_build = os.system("npm run build")
if(is_build == 0):
    print("前端构建成功，正在合并后端")
os.chdir("../deployer")
shutil.copytree(backend_dir, final_dir)
shutil.copytree(dist_dir, r'build/backend/view')
print("合并完毕，您可以可以直接把build文件夹压缩为zip部署到服务器")






