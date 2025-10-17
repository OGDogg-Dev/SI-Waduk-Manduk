import { MapContainer, Marker, Popup, TileLayer } from 'react-leaflet';
import type { LatLngExpression } from 'leaflet';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';
import { cn } from '@/lib/utils';

L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

export interface MapMarker {
    position: LatLngExpression;
    label?: string;
}

export interface MapViewProps {
    center?: LatLngExpression;
    markers?: MapMarker[];
    zoom?: number;
    className?: string;
}

const DEFAULT_CENTER: LatLngExpression = [-7.907773, 111.55616];

/**
 * Peta Leaflet dengan konfigurasi sederhana untuk menampilkan lokasi wisata.
 */
export const LeafletMapView = ({
    center,
    markers,
    zoom = 15,
    className,
}: MapViewProps) => {
    const effectiveCenter = center ?? markers?.[0]?.position ?? DEFAULT_CENTER;
    const markerList = markers && markers.length > 0 ? markers : [{ position: effectiveCenter }];

    return (
        <MapContainer
            center={effectiveCenter}
            zoom={zoom}
            className={cn('h-64 w-full rounded-2xl', className)}
            scrollWheelZoom={false}
        >
            <TileLayer
                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor'
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            {markerList.map((marker, index) => (
                <Marker key={index} position={marker.position}>
                    {marker.label && <Popup>{marker.label}</Popup>}
                </Marker>
            ))}
        </MapContainer>
    );
};

export default LeafletMapView;
