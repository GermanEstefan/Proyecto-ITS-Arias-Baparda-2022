import React from 'react'
import facebookIcon from '../../assets/img/facebook-brands.svg'
import instagramIcon from '../../assets/img/instagram-brands.svg'

const Footer = () => {
  return (
    <footer className='footer'>
    <ul>
      <li>seguridadcorporal@gmail.com</li>
      <li>092 065 001</li>
      <li>Moltke 1194, Montevideo</li>
    </ul>
    <div className='img-container'>
      <a href="#" target="_blank" rel="noopener noreferrer"
        ><img src={facebookIcon} width="50px" height='50px' alt=""
      /></a>
      <a href="#" target="_blank" rel="noopener noreferrer"
        ><img src={instagramIcon} width="50px" height='50px'  alt=""
      /></a>
    </div>
  </footer>
  )
}

export default Footer