# rafahack
 
Se deberian separar las funciones del resto del código de encrypt.py y decrypt.py.
No se ha hecho para evitar posibles problemas con pyinstaller, el paquete usado para
hacer los archivos ejecutables. Se tiene que poder hacer pero no lo he probado, no he
tenido tiempo despues de hacerlo funcionar con cryptography y multiproccessing.

pyinstaller.exe --onefile --hidden-import cryptography <archivo.py>
--onefile para que no separew el archivo en un ejecutable y librerias
--hidden-import cryptography porque pyinstaller no lo detecta automaticamente

from multiprocessing import freeze_support
freeze_support()
para multiprocccessing cuando el arechivo es un ejecutable en lugar de un .py


HAY QUE SANITIZAR LOS FORMULARIOS!!!!
