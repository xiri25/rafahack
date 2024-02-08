from pathlib import Path
import secrets
import os
import base64
import getpass
#No hay criptographjy en Windows
import cryptography
from cryptography.fernet import Fernet
from cryptography.hazmat.primitives.kdf.scrypt import Scrypt

from multiprocessing import cpu_count
from multiprocessing import Process
from multiprocessing import freeze_support

def reparto(len_list, partes):
# Siempre y cuando len(lista)>partes
    cores = partes

    #print(f'len_list:{len_list}')
    dx = len_list // cores
    mod = len_list % cores
    orden = [0]

    n = cores

    start = 0

    while n > 0:
        ptr = start + dx
        
        if mod > 0:
            ptr += 1
            mod -= 1

        orden.append(ptr)
        start = ptr
        n -= 1

    return orden

def tree(directory):
    archivos = []
    try:
        for path in sorted(directory.rglob("*")):
            file_path = Path(path)
            if file_path.is_file():
                archivo = str(path.resolve())
                archivos.append(archivo)
    except:
        pass
    return archivos, len(archivos)

def decrypt(filename, key):
    """Given a filename (str) and key (bytes), it decrypts the file and write it"""
    f = Fernet(key)
    with open(filename, "rb") as file:
        # read the encrypted data
        encrypted_data = file.read()
    # decrypt data
    try:
        decrypted_data = f.decrypt(encrypted_data)
    except cryptography.fernet.InvalidToken:
        print("[!] Invalid token, most likely the password is incorrect")
        return
    # write the original file
    with open(filename, "wb") as file:
        file.write(decrypted_data)

def decrypt_array(files, key):
    for file in files:
        decrypt(file, key)

if __name__ == "__main__":

    if os.name == 'nt':  # Check if the operating system is Windows
        freeze_support()

    passwd = input("Clave: ")
    archivos_array, len_archivos = tree(Path.cwd())
    #print(len_archivos)
    
    cores = cpu_count()

    procesos = []

    reparto_array = reparto(len_archivos, cores)

    for core in range(cores):

        start = reparto_array[core]
        end = reparto_array[core + 1]

        # Hay que pasar una tuple a args
        proceso = Process(target=decrypt_array, args=(archivos_array[start:end], passwd))
        #print(f'start:{start}, end: {end}, diff: {end - start}')
        procesos.append(proceso)
        proceso.start()

    # Espera a que acabe todos los procesos
    for proceso in procesos:
        proceso.join()