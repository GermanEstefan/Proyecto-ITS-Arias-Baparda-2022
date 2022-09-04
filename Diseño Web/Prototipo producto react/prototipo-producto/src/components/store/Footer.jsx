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
      <a href="https://www.youtube.com/watch?v=zP72t9FmhPE&ab_channel=Achilles" target="_blank" rel="noopener noreferrer"
        ><img src={facebookIcon} width="50px" height='50px' alt=""
      /></a>
      <a href="https://www.youtube.com/watch?v=8Q6EDv0BVTw&ab_channel=RickOps" target="_blank" rel="noopener noreferrer"
        ><img src={instagramIcon} width="50px" height='50px'  alt=""
      /></a>
    </div>
  </footer>
  )
}

export default Footer