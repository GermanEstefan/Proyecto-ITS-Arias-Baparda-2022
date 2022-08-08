import React from 'react'
import { Formik } from 'formik'
import { Link } from 'react-router-dom';
import Imagen from './../img/Obreros.jpg'

const Register = () => {
  return (
    <>
    <div className='form-container'>


        <img src={Imagen} width='650px' alt=''></img>
        <Formik>

            <form className='form'>
            <h1>Registrate para comenzar tu experiencia</h1>
                <div>
                    <input type="text" placeholder='Nombre'></input>
                </div>
                <div>
                    <input type="text" placeholder='Email'></input>
                </div><div>
                    <input type="text" placeholder='Contraseña'></input>
                </div>
                <div>
                    <input type="text" placeholder='Confirmar contraseña'></input>
                </div>
                <button>Registrarse</button>
                <br/>
                <Link className='link' to={'/login'}>Ingresar</Link>
            </form>
        </Formik>
    </div>
    </>
  )
}

export default Register