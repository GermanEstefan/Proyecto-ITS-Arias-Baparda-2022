import React from 'react'
import { Formik } from 'formik'
import { Link } from 'react-router-dom';
import Imagen from './../img/Obreros.jpg'

const Register = () => {
  return (
    <>
    <div className='form-container'>


        <img src={Imagen} width='650px'></img>
        <Formik>

            <form className='form'>
            <h1>Registrate para comenzar tu experiencia</h1>
                <div>
                    <input placeholder='Nombre'></input>
                </div>
                <div>
                    <input placeholder='Email'></input>
                </div><div>
                    <input placeholder='Contraseña'></input>
                </div>
                <div>
                    <input placeholder='Confirmar contraseña'></input>
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