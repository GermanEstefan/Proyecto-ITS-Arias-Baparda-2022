import React from 'react'
import { Formik } from 'formik'
import { Link } from 'react-router-dom';
import Imagen from './../img/Obreros.jpg'

const Login = () => {
  return (
    <>
    <div className='form-container'>


        <img src={Imagen} width='650px'></img>
        <Formik>

            <form className='form'>
                <p>Bienvenido, por favor ingresa tus datos</p>
                <div>
                    <input placeholder='Email'></input>
                </div>
                <div>
                    <input placeholder='Contraseña'></input>
                </div>
                <button>Ingresar</button>
                <br/>
                <Link className='link' to={'/register'}>Registrarse</Link>
            </form>
        </Formik>
    </div>
    </>
  )
}

export default Login