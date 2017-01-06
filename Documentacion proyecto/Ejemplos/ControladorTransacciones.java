package ve.com.megasoft.paymentgatewayuniversal.controller;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.security.cert.X509Certificate;
import javax.net.ssl.HostnameVerifier;
import javax.net.ssl.HttpsURLConnection;
import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSession;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;
import sun.misc.BASE64Encoder;

public class ControladorTransacciones {

	private String credenciales, resultado="";

	/**
	 * Constructor de la clase.
	 */
	public ControladorTransacciones() {
	}

	/**
	 * Metodo para codificar a base64 la credencial del usuario.
	 * @param fuente
	 * @return
	 */
	public String codificar(String fuente) {
		BASE64Encoder enc = new sun.misc.BASE64Encoder();
		return (enc.encode(fuente.getBytes()));
	}// fin del metodo codificar.

	/**
	 *Metodo que valida el certificado aunque este se encuentre
	 *vencido.
	 */
	public static final void createTrustManager() {
		TrustManager trustAllCerts[] = { new X509TrustManager() {
			public X509Certificate[] getAcceptedIssuers() {
				return null;
			}

			public void checkClientTrusted(X509Certificate ax509certificate[],
					String s) {
			}

			public void checkServerTrusted(X509Certificate ax509certificate[],
					String s) {
			}
		} };
		try {
			SSLContext sc = SSLContext.getInstance("SSL");
			sc.init(null, trustAllCerts, new SecureRandom());
			HttpsURLConnection
					.setDefaultSSLSocketFactory(sc.getSocketFactory());
			HttpsURLConnection
					.setDefaultHostnameVerifier(new HostnameVerifier() {
						public boolean verify(String string, SSLSession ssls) {
							return true;
						}
					});
		} catch (Exception e) {
			e.printStackTrace();
		}
	}// fin del metodo createTrustManager.


	/**
	 * Metodo que realiza la transaccion de pago en conjunto con el
	 * paymentGateway, a traves de un protocolo https basic autentificado.
	 * @throws IOException
	 * @throws NoSuchAlgorithmException
	 */
	public String pagar(String usuario, String clave, String url_preregistro)
			throws IOException, NoSuchAlgorithmException {

		/**
		 * Se realiza porque el certificado puede
		 * no estar vigente y este metodo le otorga
		 * uno en su defecto.
		 */
		  createTrustManager();
		  URL url = new URL(url_preregistro); URLConnection conn;
		  conn =url.openConnection();
		  setCredenciales(usuario + ":" + clave);
		  conn.setRequestProperty("Authorization", "Basic " +
		  codificar(getCredenciales()));
		// captura la respuesta.
		  BufferedReader rd = new BufferedReader(new InputStreamReader(conn
		  .getInputStream()));
		  String line;
		  while ((line = rd.readLine()) != null)
		   {
			  setResultado(line);

		   }
		  rd.close();

		  return getResultado();
	}// fin del metodo buscaNumeroDeControl.
	/**
	 * Metodo que se realiza
	 * para verificar con el paymentGateway que el
	 * pago se hizo por el monto correcto.
	 * @param urlRedirect
	 * @param usuario
	 * @param clave
	 * @return
	 * @throws IOException
	 */
	public String verificar(String urlRedirect, String usuario,
			String clave) throws IOException {

		/**
		 * Se realiza porque el certificado puede
		 * no estar vigente y este metodo le otorga
		 * uno en su defecto.
		 */
		createTrustManager();

		URL url = new URL(urlRedirect);
		URLConnection conn;
		conn = url.openConnection();
		setCredenciales(usuario + ":" + clave);
		conn.setRequestProperty("Authorization", "Basic "
				+ codificar(getCredenciales()));
		BufferedReader rd = new BufferedReader(new InputStreamReader(conn
				.getInputStream()));
		String line="";
		  while ((line = rd.readLine()) != null)
		   {
			  setResultado(getResultado()+"\n"+line);

		   }
		//setResultado(rd.readLine());
		rd.close();

		return getResultado();

	}// fin del metodo verificar.

	/**
	 * Set & Get
	 *
	 * @param credenciales
	 */
	public void setCredenciales(String credenciales) {
		this.credenciales = credenciales;
	}

	public String getCredenciales() {
		return credenciales;
	}

	public void setResultado(String resultado) {
		this.resultado = resultado;
	}

	public String getResultado() {
		return resultado;
	}

}
