import React from 'react'
import facebookIcon from './../img/facebook-brands.svg'
import instagramIcon from './../img/instagram-brands.svg'

const Footer = () => {
  return (
    <div className='footer'>
    <ul>
      <li>seguridadcorporal@gmail.com</li>
      <li>092 065 001</li>
      <li>Moltke 1194, Montevideo</li>
    </ul>
    <div className='img-container'>
      <a href=""
        ><img src={facebookIcon} width="50px" height='50px' alt=""
      /></a>
      <a href=""
        ><img src={instagramIcon} width="50px" height='50px'  alt=""
      /></a>
    </div>
  </div>
  )
}

export default Footer