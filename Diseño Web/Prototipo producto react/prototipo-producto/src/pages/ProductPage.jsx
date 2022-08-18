import React, {useEffect} from 'react'

const ProductPage = (name, description, img) => {
  useEffect(() => {
    window.scroll(0, 0);
  }, []);
  console.log(name, description)
  return (
    <div style={{marginTop:'8em'}}>
      asasdasd
    </div>
  )
}

export default ProductPage