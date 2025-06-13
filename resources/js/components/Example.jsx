import React, { Component } from 'react';

class Example extends Component {
    
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            formulario: {},
            error: null,
            protocol:'',
            have_img : false,     
            server: props.server,
            cambiar_pass:false,
            timestamp: Date.now(),
          
            
        };
        this.handleChange = this.handleChange.bind(this);
        this.save_profile = this.save_profile.bind(this);
        this.sendingPass = this.sendingPass.bind(this);
        this.cambiar_pass = this.cambiar_pass.bind(this);
        this.handleCheckboxChange = this.handleCheckboxChange.bind(this);
        this.handleImageSelect = this.handleImageSelect.bind(this);

        this.sendingImg = this.sendingImg.bind(this);
        this.checaesto = this.checaesto.bind(this);
       
      
        console.log("creating componentx")
    }

    async componentDidMount() {

      console.log(this.state.server)

        const protocol = this.state.server + "/api/my-profile"
        this.setState({ protocol: protocol });
          console.log(protocol)
        // console.log(this.props.token)
        try {
          const response = await fetch(protocol, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',  
              Authorization: "Bearer " + this.props.token,
            },
            credentials: "include",
            body: JSON.stringify({'user':this.props.name})
          });
          const data = await response.json();
          const aux_data = data["user"];
         console.log(data);
          this.setState({ loading: false, formulario: aux_data });

        
       
          
        } catch (error) {
          this.setState({ loading: false, error: error });
        }
    }


    async sendingInfo(){
      // console.log(this.state.formulario)
    
      const protocol = this.state.server + "/api/actualizar-perfil"
      this.setState({ protocol: protocol });
      // console.log(protocol)
      // console.log(this.props.token)
      try {
        const response = await fetch(protocol, {
          method: 'POST',
          headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',  
            Authorization: "Bearer " + this.props.token,
          },
          credentials: "include",
          body: JSON.stringify(this.state.formulario)
        });
        const data = await response.json();
      
         if(data['code']===200){
          alert(data['response'])
        }else{
          alert(data['response'])
        }
       
      } catch (error) {
      
      }
  
    }
    async sendingPass(){
     
      let mybody={
        "email" :this.state.formulario.email,
        "old_pass": this.state.formulario.old_pass,
        "new_pass":this.state.formulario.new_pass
      }
      // console.log(mybody)
      
      const protocol = this.state.server + "/api/change-pass"
      this.setState({ protocol: protocol });
      // console.log(protocol)
      // console.log(this.props.token)
      try {
        const response = await fetch(protocol, {
          method: 'POST',
          headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',  
            Authorization: "Bearer " + this.props.token,
          },
          credentials: "include",
          body: JSON.stringify(mybody)
        });
        const data = await response.json();

        if(data['code']===200){
          alert(data['response'])
        }else{
          alert(data['response'])
        }
      
        // console.log(data);
       
      } catch (error) {
      
      }
  
    }
    cambiar_pass(){
     

      this.setState(prevState => ({cambiar_pass: !prevState.cambiar_pass }));
     


    }

  

   async sendingImg(event){

    this.setState({
      timestamp: Date.now(),
    });

    const formData = new FormData();
    formData.append('imagen', this.state.formulario.imagen);
    formData.append('id',this.props.name)
      
      const protocol = this.state.server + "/api/up-imgprofile"
  
      try {
        const response = await fetch(protocol, {
          method: 'POST',
          headers: {
              
            Authorization: "Bearer " + this.props.token,
          },
          credentials: "include",
          body: formData
        });
        const data = await response.json();

        if(data['code']===200){
          alert(data['response'])
        }else{
          alert(data['response'])
        }
      
        // console.log(data);
       
      } catch (error) {
      
      }
    }

    handleImageSelect(event) {
      const selectedImage = event.target.files[0];
      // Aquí puedes validar la imagen seleccionada antes de establecerla como la nueva imagen de perfil
      this.setState({ formulario:{'imagen':selectedImage}});
      this.setState({ have_img: true});
    }
    handleCheckboxChange = (event) => {

      this.setState({
        formulario: {
          ...this.state.formulario, // conserva los valores existentes del estado
          'public_info': event.target.checked,
        },
      });

      // this.setState({ formulario:{public_info: event.target.checked }}, () => {
      //   console.log(this.state.share_info); // Esto imprimirá el valor actualizado
      // });
    }

    save_profile(){
      this.sendingInfo();
    }

 

      handleChange(event) {
    
        const name = event.currentTarget.name;
        const value = event.currentTarget.value;
        this.setState({
          formulario: {
            ...this.state.formulario, // conserva los valores existentes del estado
            [name]: value,
          },
        });
      }

   
       checaesto = async () => {
       
        const protocol = this.state.server + "/api/del-imgprofile"
    
        try {
          const response = await fetch(protocol, {
            method: 'POST',
            headers: {
                
              Authorization: "Bearer " + this.props.token,
            },
            credentials: "include",
          });
          const data = await response.json();
          console.log(data)
  
          if(data['code']===200){
            alert(data['response'])
            this.setState({ formulario:{'imagen':null}});
          }else{
            alert(data['response'])
          }
        
          // console.log(data);
         
        } catch (error) {
        
        }
      }
    

      confirmDel = () => {
        if (window.confirm("¿Desea eliminar la imagen?")) {
           // Code to delete the image
           this.checaesto()
        }
      }

    render() {
      
      //  this.setState({share_info:this.state.formulario.public_info})

      
        const { loading, error } = this.state;
        var haveImg;
        try {
          haveImg = this.state.formulario.imagen;
        } catch (error) {
          haveImg = null
        }
      

        let divImg=null;
     
        const cambiando_pass = this.state.cambiar_pass
        let div_pass =null
        if(cambiando_pass){
          div_pass =
          <div className="">
              <label>Antigua contraseña</label>
              <input id="old_pass" name="old_pass" type="text" className="form-control" placeholder="*********"
                    onChange={this.handleChange}
                    />
                    <label>Nueva contraseña</label>
              <input id="new_pass" name="new_pass" type="text" className="form-control" placeholder="*********"
                    onChange={this.handleChange}
                    />

              <button className="btn btn-primary btn-sm mt-2" type="button"
                onClick={this.sendingPass}
                >Actualizar contraseña
                
                </button>
          </div>
        }

        let source
        let icon_trash=""
        // console.log(haveImg)
        if(haveImg === null){
          source = "https://findallmx.herokuapp.com/public/imagenes/avatar.png"
        }else{
         
         if(haveImg instanceof File || haveImg instanceof Blob){
       
          source =URL.createObjectURL(haveImg)
         }else{
          icon_trash=  <i onClick={this.confirmDel} className="fas fa-trash ms-4"></i>
          source = "https://storage.googleapis.com/findall_bucket/" + haveImg + "?t=" + this.state.timestamp
         }
        
       
        }
        if(this.state.have_img){
          divImg=<div>
          <button className='mb-2' onClick={this.sendingImg} type="submit">Guardar imagen</button>
        </div>
        }
      

       
        if (loading) {
            return <p>Cargando...</p>;
        }

        if (error) {
            return <p>Error: {error.message}</p>;
        }

        return (
        <>
        <p></p>
      
        <div className="container rounded bg-white mt-5 mb-5">
          <div className="row">
          <div className="col-md-3 border-right">
            <div className="d-flex flex-column align-items-center text-center p-3 py-5">


            <img alt='img-profile' className="rounded-circle mt-5" width="150px" src={source} />
            <div>
            <label htmlFor="imagen">
            <i className="fas fa-edit"></i>
          
              </label>
              <label>
            {icon_trash}
              </label>
              </div>  
            <input 
           
            type="file" 
            id="imagen"
            name="imagen" 
            onChange={this.handleImageSelect} 
            accept="image/*" 
            style={{ visibility:'hidden' }}
             />
 
            {divImg}
           
           
                <span className="font-weight-bold">{this.state.formulario.nombre} {this.state.formulario.ap}</span>
                <span className="text-black-50">{this.state.formulario.email}</span><span> </span>
                
                <button onClick={this.cambiar_pass}>
                  {/* {this.state.cambiar_pass ? 'Encendido' : 'Apagado'} */}
                  <i className="fa fa-unlock-alt" aria-hidden="true"></i>
                 
                </button>
                {div_pass}
               
            </div>
        </div>
        <div className="col-md-8 border-right">
          <div className="p-3 py-5">
            <div className="d-flex justify-content-between align-items-center mb-3">
              <h4 className="text-right">Datos del perfil</h4>
            </div>

            <div className="row mt-2">
                    <div className="col-md-4"><label className="labels">Nombre</label>
                    <input id="name" name="nombre" type="text" className="form-control" placeholder="Nombre"
                    value={this.state.formulario.nombre} 
                    onChange={this.handleChange}
                    /></div>
                    <div className="col-md-4"><label className="labels">Apellido paterno</label>
                    <input name="ap" type="text" className="form-control"  placeholder="Apellido paterno"
                    value={this.state.formulario.ap} 
                    onChange={this.handleChange}
                    /></div>
                    <div className="col-md-4"><label className="labels">Apellido materno</label>
                        <input 
                        name="am"
                        type="text" className="form-control"  placeholder="Apellido materno"
                        value={this.state.formulario.am} 
                        onChange={this.handleChange}
                        /></div>
                </div>
                <div className="row mt-3">
                    <div className="col-md-12"><label className="labels">Dirección</label>
                        <input 
                        name="direccion"
                        type="text" className="form-control" placeholder="Dirección completa" 
                        value={this.state.formulario.direccion} 
                        onChange={this.handleChange}
                        />
                    </div>
                    <div className="col-md-4"><label className="labels">Ciudad</label>
                    <input
                    name="ciudad"
                    type="text" className="form-control" placeholder="Ciudad" 
                    value={this.state.formulario.ciudad} 
                    onChange={this.handleChange}
                    />
                    </div>
                    <div className="col-md-4"><label className="labels">Estado</label>
                    <input 
                    name="estado"
                    type="text" className="form-control" placeholder="Estado" 
                    value={this.state.formulario.estado} 
                    onChange={this.handleChange}
                    />

                    </div>
                    <div className="col-md-4"><label className="labels">Código Postal</label>
                    <input 
                    name="cp"
                    type="text" className="form-control" placeholder="Código Postal" 
                    value={this.state.formulario.cp} 
                    onChange={this.handleChange}
                    />

                    </div>
                    <div className="col-md-6"><label className="labels">Télefono</label>
                    <input 
                    name="telefono"
                    type="number" className="form-control" placeholder="Número teléfonico" 
                    value={this.state.formulario.telefono} 
                    onChange={this.handleChange}
                    />

                    </div>
                    <div className="col-md-6">
                    <div className="form-check form-switch mt-4">
                      <input 
                      checked={this.state.formulario.public_info} 
                      onChange={this.handleCheckboxChange}
                      value={this.state.formulario.public_info}
                      className="form-check-input" 
                      type="checkbox" role="switch" 
                      id="flexSwitchCheckDefault" />
                      <label className="form-check-label" >Datos públicos</label>
                    </div>

                    </div>
                    
                </div>
                
                <div className="mt-5 text-center">
                <button className="btn btn-primary profile-button" type="button"
                onClick={this.save_profile}
                >Actualizar información
                
                </button></div>
          </div>
          </div>
          </div>
        </div>
      


        </>
        );
    }
}

export default Example;
